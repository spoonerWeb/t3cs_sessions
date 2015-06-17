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

/**
 * Class RoomController
 * @package T3CS\T3csSessions\Controller
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class RoomController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * roomRepository
	 *
	 * @var \T3CS\T3csSessions\Domain\Repository\RoomRepository
	 * @inject
	 */
	protected $roomRepository = NULL;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$rooms = $this->roomRepository->findAll();
		$this->view->assign('rooms', $rooms);
	}

}