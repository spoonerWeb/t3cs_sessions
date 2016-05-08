<?php
namespace T3CS\T3csSessions\Controller;

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
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Class SessionController
 *
 * @package T3CS\T3csSessions\Controller
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class SessionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * sessionRepository
     *
     * @var \T3CS\T3csSessions\Domain\Repository\SessionRepository
     * @inject
     */
    protected $sessionRepository = null;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        if ($this->settings['showPastSessions']) {
            $this->redirect('listPast');
        }
        $this->sessionRepository->setDefaultOrderings(
            [
                'slot.begin' => QueryInterface::ORDER_ASCENDING,
                'room.name' => QueryInterface::ORDER_ASCENDING
            ]
        );
        $sessions = $this->sessionRepository->findAll();
        $this->view->assign('sessions', $sessions);
    }

    /**
     * action list
     *
     * @return void
     */
    public function listPastAction()
    {
        $this->sessionRepository->setDefaultOrderings(
            [
                'slot.begin' => QueryInterface::ORDER_ASCENDING,
                'room.name' => QueryInterface::ORDER_ASCENDING
            ]
        );
        $sessions = $this->sessionRepository->findAll();
        $this->view->assign('sessions', $sessions);
    }
}