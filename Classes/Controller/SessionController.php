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
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SessionController
 * @package T3CS\T3csSessions\Controller
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class SessionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * sessionRepository
	 *
	 * @var \T3CS\T3csSessions\Domain\Repository\SessionRepository
	 * @inject
	 */
	protected $sessionRepository = NULL;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$this->sessionRepository->setDefaultOrderings(
			array(
				'slot.begin' => QueryInterface::ORDER_ASCENDING,
				'room.name' => QueryInterface::ORDER_ASCENDING
			)
		);
		$sessions = $this->sessionRepository->findAll();
		$this->view->assign('sessions', $sessions);
	}
}