<?php
namespace T3CS\T3csSessions\Domain\Model;

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
 * Class Slot
 *
 * @package T3CS\T3csSessions\Domain\Model
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class Slot extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * begin
     *
     * @var \DateTime
     */
    protected $begin = null;

    /**
     * end
     *
     * @var \DateTime
     */
    protected $end = null;

    /**
     * @var bool
     */
    protected $isBreak = false;

    /**
     * Returns the begin
     *
     * @return \DateTime $begin
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * Sets the begin
     *
     * @param \DateTime $begin
     * @return void
     */
    public function setBegin(\DateTime $begin)
    {
        $this->begin = $begin;
    }

    /**
     * Returns the end
     *
     * @return \DateTime $end
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Sets the end
     *
     * @param \DateTime $end
     * @return void
     */
    public function setEnd(\DateTime $end)
    {
        $this->end = $end;
    }

    /**
     * Gets isBreak
     *
     * @return boolean
     */
    public function getIsBreak()
    {
        return $this->isBreak;
    }

    /**
     * Sets isBreak
     *
     * @param boolean $isBreak
     */
    public function setIsBreak($isBreak)
    {
        $this->isBreak = $isBreak;
    }
}