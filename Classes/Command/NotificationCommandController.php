<?php
namespace T3CS\T3csSessions\Command;

/**
 * This file is part of the TYPO3 CMS project.
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

use T3CS\T3csSessions\Domain\Repository\SessionRepository;
use T3CS\T3csSessions\Domain\Model\Session;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use Minishlink\WebPush\WebPush;
use Codebird\Codebird;

/**
 * Class NotificationCommandController
 *
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class NotificationCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController
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
    public function injectSessionRepository(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * @return void
     */
    public function init()
    {
        $this->extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3cs_sessions']);
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
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
            $sessions = $this->sessionRepository->findNextSessionsWithinMinutes(
                $this->extensionConfiguration['sendNotificationsMinutesBefore']
            );
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

            $sessions = $this->sessionRepository->findNextSessionsWithinMinutes(
                $this->extensionConfiguration['sendNotificationsMinutesBefore']
            );
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

            $webPush = new WebPush($auth, $defaultOptions);

            $sessions = $this->sessionRepository->findNextSessionsWithinMinutes($this->extensionConfiguration['sendNotificationsMinutesBefore']);
            foreach ($sessions as $session) {
                $devices = $this->getAllDevicesWantNotified($session->getUid());
                if (!empty($devices)) {
                    foreach ($devices as $device) {
                        $subscriptionData = unserialize($device['subscription_data']);
                        $payload = [
                            'title' => $session->getTitle(),
                            'body' => 'in ' . $session->getSlot()->getBegin()->diff(new \DateTime())->format('%i') . ' Minuten im Raum ' . $session->getRoom()->getName() . '.'
                        ];
                        $webPush->sendNotification(
                            $subscriptionData['endpoint'],
                            json_encode($payload),
                            $subscriptionData['keys']['p256dh'],
                            $subscriptionData['keys']['auth']
                        );
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
    protected function getStatusMessage(Session $session)
    {
        $label = $session->getSlot()->getIsBreak() ? 'twitterNotificationBreak' : 'twitterNotification';
        $author = ($session->getAuthor() && !$session->getSlot()->getIsBreak()) ? $session->getAuthor() : '';
        $status = LocalizationUtility::translate(
            $label,
            'T3csSessions',
            [
                $session->getTitle(),
                $session->getSlot()->getBegin()->format('H:i \U\h\r'),
                $author,
                $session->getRoom()->getName()
            ]
        );

        if (strlen($status) > 140) {
            $titleLength = strlen($session->getTitle());
            $overhead = strlen($status) - 140;
            $newTitle = substr($session->getTitle(), 0, $titleLength - $overhead - 3) . '...';
            $status = LocalizationUtility::translate(
                $label, 'T3csSessions', [
                    $newTitle,
                    $session->getSlot()->getBegin()->format('H:i \U\h\r'),
                    $author,
                    $session->getRoom()->getName()
                ]
            );
        }

        return $status;
    }

    /**
     * @return boolean
     */
    protected function allRequirementsForTwitterNotifications()
    {
        return $this->extensionConfiguration['twitterApiConsumerKey'] && $this->extensionConfiguration['twitterApiSecretKey'] &&
        $this->extensionConfiguration['twitterApiAccessToken'] && $this->extensionConfiguration['twitterApiAccessTokenSecret'];
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
        Codebird::setConsumerKey(
            $this->extensionConfiguration['twitterApiConsumerKey'],
            $this->extensionConfiguration['twitterApiSecretKey']
        );
        $codebird = Codebird::getInstance();
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
        $devices = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'token, subscription_data',
            'tx_t3cssessions_domain_model_device
            LEFT JOIN tx_t3cssessions_device_session_mm ON tx_t3cssessions_device_session_mm.uid_local = tx_t3cssessions_domain_model_device.uid',
            'tx_t3cssessions_device_session_mm.uid_foreign = ' . (int)$sessionUid . ' AND token != "undefined" AND subscription_data != ""',
            'tx_t3cssessions_device_session_mm.uid_local'
        );

        return $devices;
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    private function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
