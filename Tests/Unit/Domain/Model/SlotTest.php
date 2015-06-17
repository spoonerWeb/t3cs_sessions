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
 * Test case for class \T3CS\T3csSessions\Domain\Model\Slot.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class SlotTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \T3CS\T3csSessions\Domain\Model\Slot
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \T3CS\T3csSessions\Domain\Model\Slot();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getBeginReturnsInitialValueForDateTime() {
		$this->assertEquals(
			NULL,
			$this->subject->getBegin()
		);
	}

	/**
	 * @test
	 */
	public function setBeginForDateTimeSetsBegin() {
		$dateTimeFixture = new \DateTime();
		$this->subject->setBegin($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'begin',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getEndReturnsInitialValueForDateTime() {
		$this->assertEquals(
			NULL,
			$this->subject->getEnd()
		);
	}

	/**
	 * @test
	 */
	public function setEndForDateTimeSetsEnd() {
		$dateTimeFixture = new \DateTime();
		$this->subject->setEnd($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'end',
			$this->subject
		);
	}
}
