<?php
namespace T3CS\T3csSessions\Command;

/*
 * This file is part of a TYPO3 extension.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

class NotificationCommand extends \Symfony\Component\Console\Command\Command
{

    /**
     * @var \T3CS\T3csSessions\Domain\Repository\SessionRepository
     */
    protected $sessionRepository;

    /**
     * @var array
     */
    protected $extensionConfiguration = [];

    /**
     * @param \T3CS\T3csSessions\Domain\Repository\SessionRepository $sessionRepository
     * @return void
     */
    public function injectSessionRepository(\T3CS\T3csSessions\Domain\Repository\SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }


    public function configure()
    {
        $this->setDescription('Sends notification for upcoming events');
    }

    public function execute()
    {
        $this->notificationCommand();
    }

    /**
     * @return void
     */
    public function init()
    {
        $this->extensionConfiguration = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)
            ->get('t3cs_sessions');
        $querySettings = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->sessionRepository->setDefaultQuerySettings($querySettings);
    }

    /**
     * @return void
     */
    public function testNotificationCommand()
    {
        $this->init();
        if ($this->extensionConfiguration['enableTwitterNotification'] && $this->allRequirementsForTwitterNotifications()) {
            $sessions = $this->sessionRepository->findNextSessionsWithinMinutes($this->extensionConfiguration['sendNotificationsMinutesBefore']);
            foreach ($sessions as $session) {
                $statusMessage = $this->getStatusMessage($session);
                $params = [
                    'status' => $statusMessage,
                    'length' => strlen($statusMessage)
                ];

                print_r($params);
            }
        }
    }

    /**
     * @return void
     */
    public function notificationCommand()
    {
        $this->init();
        if ($this->extensionConfiguration['enableTwitterNotification'] && $this->allRequirementsForTwitterNotifications()) {
            $twitter = $this->getTwitterLibrary();

            $sessions = $this->sessionRepository->findNextSessionsWithinMinutes($this->extensionConfiguration['sendNotificationsMinutesBefore']);
            foreach ($sessions as $session) {
                $statusMessage = $this->getStatusMessage($session);
                $params = [
                    'status' => $statusMessage
                ];

                $twitter->statuses_update($params);
            }
        }

        if ($this->extensionConfiguration['enablePushNotification'] && $this->allRequirementsForPushNotifications()) {
            $auth = [
                'VAPID' => [
                    'subject' => $this->extensionConfiguration['pushNotificationSubject'],
                    'publicKey' => $this->extensionConfiguration['pushNotificiationPublicKey'],
                    'privateKey' => $this->extensionConfiguration['pushNotificiationPrivateKey'],
                ]
            ];
            $defaultOptions = [
                'TTL' => 0,
                'urgency' => 'high',
                'topic' => 't3cs_session'
            ];

            $webPush = new \Minishlink\WebPush\WebPush($auth, $defaultOptions);

            $sessions = $this->sessionRepository->findNextSessionsWithinMinutes($this->extensionConfiguration['sendNotificationsMinutesBefore']);
            foreach ($sessions as $session) {
                $devices = $this->getAllDevicesWantNotified($session->getUid());
                if (!empty($devices)) {
                    foreach ($devices as $device) {
                        $subscriptionData = unserialize($device['subscription_data']);
                        $payload = [
                            'title' => $session->getTitle(),
                            'body' => 'in ' . $session->getSlot()
                                    ->getBegin()
                                    ->diff(new \DateTime())
                                    ->format('%i') . ' Minuten im Raum ' . $session->getRoom()->getName() . '.'
                        ];
                        $webPush->sendNotification($subscriptionData['endpoint'], json_encode($payload),
                            $subscriptionData['keys']['p256dh'], $subscriptionData['keys']['auth']);
                    }
                    $webPush->flush();
                }
            }
        }
    }

    /**
     * @param \T3CS\T3csSessions\Domain\Model\Session $session
     * @return NULL|string
     */
    protected function getStatusMessage(\T3CS\T3csSessions\Domain\Model\Session $session)
    {
        $label = $session->getSlot()->getIsBreak() ? 'twitterNotificationBreak' : 'twitterNotification';
        $author = ($session->getAuthor() && !$session->getSlot()->getIsBreak()) ? $session->getAuthor() : '';
        $status = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($label, 'T3csSessions', [
            $session->getTitle(),
            $session->getSlot()->getBegin()->format('H:i \U\h\r'),
            $author,
            $session->getRoom()->getName()
        ]);

        if (strlen($status) > 140) {
            $titleLength = strlen($session->getTitle());
            $overhead = strlen($status) - 140;
            $newTitle = substr($session->getTitle(), 0, $titleLength - $overhead - 3) . '...';
            $status = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($label, 'T3csSessions', [
                $newTitle,
                $session->getSlot()->getBegin()->format('H:i \U\h\r'),
                $author,
                $session->getRoom()->getName()
            ]);
        }

        return $status;
    }

    /**
     * @return boolean
     */
    protected function allRequirementsForTwitterNotifications()
    {
        return $this->extensionConfiguration['twitterApiConsumerKey'] && $this->extensionConfiguration['twitterApiSecretKey'] && $this->extensionConfiguration['twitterApiAccessToken'] && $this->extensionConfiguration['twitterApiAccessTokenSecret'];
    }

    /**
     * @return boolean
     */
    protected function allRequirementsForPushNotifications()
    {
        return class_exists(\Minishlink\WebPush\WebPush::class) && $this->extensionConfiguration['pushNotificiationPublicKey'] && $this->extensionConfiguration['pushNotificiationPrivateKey'];
    }

    /**
     * @return \Codebird\Codebird
     */
    protected function getTwitterLibrary()
    {
        \Codebird\Codebird::setConsumerKey($this->extensionConfiguration['twitterApiConsumerKey'],
            $this->extensionConfiguration['twitterApiSecretKey']);
        $codebird = \Codebird\Codebird::getInstance();
        $codebird->setToken(
            $this->extensionConfiguration['twitterApiAccessToken'],
            $this->extensionConfiguration['twitterApiAccessTokenSecret']
        );

        return $codebird;
    }

    /**
     * @param $sessionUid
     * @return array|NULL
     */
    protected function getAllDevicesWantNotified($sessionUid)
    {
        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getQueryBuilderForTable('tx_t3cssessions_domain_model_device');
        $devices = $queryBuilder->select('token', 'subscription_data')
            ->from('tx_t3cssessions_domain_model_device', 'd')
            ->leftJoin('tx_t3cssessions_device_session_mm', 'dsmm', $queryBuilder->expr()->eq('dsmm.uid_local', 'd.uid'))
            ->where([
                $queryBuilder->expr()->eq('dsmm.uid_foreign', (int)$sessionUid),
                $queryBuilder->expr()->neq('d.token', 'undefined'),
                $queryBuilder->expr()->neq('d.subscription_data', '')
            ])
            ->groupBy('dsmm.uid_local')
            ->execute()
            ->fetchAll();

        return $devices;
    }

}
