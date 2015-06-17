<?php
namespace T3CS\T3csSessions\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Thomas Löffler <loeffler@spooner-web.de>, Spooner Web
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class T3CS\T3csSessions\Controller\SessionController.
 *
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class SessionControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \T3CS\T3csSessions\Controller\SessionController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('T3CS\\T3csSessions\\Controller\\SessionController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllSessionsFromRepositoryAndAssignsThemToView() {

		$allSessions = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$sessionRepository = $this->getMock('T3CS\\T3csSessions\\Domain\\Repository\\SessionRepository', array('findAll'), array(), '', FALSE);
		$sessionRepository->expects($this->once())->method('findAll')->will($this->returnValue($allSessions));
		$this->inject($this->subject, 'sessionRepository', $sessionRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('sessions', $allSessions);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenSessionToView() {
		$session = new \T3CS\T3csSessions\Domain\Model\Session();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newSession', $session);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($session);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenSessionToSessionRepository() {
		$session = new \T3CS\T3csSessions\Domain\Model\Session();

		$sessionRepository = $this->getMock('T3CS\\T3csSessions\\Domain\\Repository\\SessionRepository', array('add'), array(), '', FALSE);
		$sessionRepository->expects($this->once())->method('add')->with($session);
		$this->inject($this->subject, 'sessionRepository', $sessionRepository);

		$this->subject->createAction($session);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenSessionToView() {
		$session = new \T3CS\T3csSessions\Domain\Model\Session();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('session', $session);

		$this->subject->editAction($session);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenSessionInSessionRepository() {
		$session = new \T3CS\T3csSessions\Domain\Model\Session();

		$sessionRepository = $this->getMock('T3CS\\T3csSessions\\Domain\\Repository\\SessionRepository', array('update'), array(), '', FALSE);
		$sessionRepository->expects($this->once())->method('update')->with($session);
		$this->inject($this->subject, 'sessionRepository', $sessionRepository);

		$this->subject->updateAction($session);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenSessionFromSessionRepository() {
		$session = new \T3CS\T3csSessions\Domain\Model\Session();

		$sessionRepository = $this->getMock('T3CS\\T3csSessions\\Domain\\Repository\\SessionRepository', array('remove'), array(), '', FALSE);
		$sessionRepository->expects($this->once())->method('remove')->with($session);
		$this->inject($this->subject, 'sessionRepository', $sessionRepository);

		$this->subject->deleteAction($session);
	}
}
