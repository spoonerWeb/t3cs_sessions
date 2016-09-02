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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class NotificationCommandController
 *
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class NotificationCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController
{

    /**
     * @var \T3CS\T3csSessions\Domain\Repository\SessionRepository
     * @inject
     */
    protected $sessionRepository;

    /**
     * @var array
     */
    protected $extensionConfiguration = [];

    /**
     * @return void
     */
    public function init()
    {
        $this->extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3cs_sessions']);
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
                /** @var \T3CS\T3csSessions\Domain\Model\Session $session */
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
                /** @var \T3CS\T3csSessions\Domain\Model\Session $session */
                $statusMessage = $this->getStatusMessage($session);
                $params = [
                    'status' => $statusMessage
                ];

                $twitter->statuses_update($params);
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
        $status = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
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
            $status = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
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
     * @return \Codebird\Codebird
     */
    protected function getTwitterLibrary()
    {
        \Codebird\Codebird::setConsumerKey(
            $this->extensionConfiguration['twitterApiConsumerKey'],
            $this->extensionConfiguration['twitterApiSecretKey']
        );
        $codebird = \Codebird\Codebird::getInstance();
        $codebird->setToken(
            $this->extensionConfiguration['twitterApiAccessToken'],
            $this->extensionConfiguration['twitterApiAccessTokenSecret']
        );

        return $codebird;
    }

}