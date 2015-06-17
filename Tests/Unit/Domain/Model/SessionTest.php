<?php

namespace T3CS\T3csSessions\Tests\Unit\Domain\Model;

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
 * Test case for class \T3CS\T3csSessions\Domain\Model\Session.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class SessionTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \T3CS\T3csSessions\Domain\Model\Session
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \T3CS\T3csSessions\Domain\Model\Session();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getTitle()
		);
	}

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle() {
		$this->subject->setTitle('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'title',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getAuthorReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getAuthor()
		);
	}

	/**
	 * @test
	 */
	public function setAuthorForStringSetsAuthor() {
		$this->subject->setAuthor('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'author',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getTagsReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getTags()
		);
	}

	/**
	 * @test
	 */
	public function setTagsForStringSetsTags() {
		$this->subject->setTags('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'tags',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getRoomReturnsInitialValueForRoom() {
		$this->assertEquals(
			NULL,
			$this->subject->getRoom()
		);
	}

	/**
	 * @test
	 */
	public function setRoomForRoomSetsRoom() {
		$roomFixture = new \T3CS\T3csSessions\Domain\Model\Room();
		$this->subject->setRoom($roomFixture);

		$this->assertAttributeEquals(
			$roomFixture,
			'room',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getSlotReturnsInitialValueForSlot() {
		$this->assertEquals(
			NULL,
			$this->subject->getSlot()
		);
	}

	/**
	 * @test
	 */
	public function setSlotForSlotSetsSlot() {
		$slotFixture = new \T3CS\T3csSessions\Domain\Model\Slot();
		$this->subject->setSlot($slotFixture);

		$this->assertAttributeEquals(
			$slotFixture,
			'slot',
			$this->subject
		);
	}
}
