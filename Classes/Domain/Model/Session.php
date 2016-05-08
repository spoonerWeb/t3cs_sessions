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
 * Class Session
 *
 * @package T3CS\T3csSessions\Domain\Model
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class Session extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * author
     *
     * @var string
     */
    protected $author = '';

    /**
     * tags
     *
     * @var string
     */
    protected $tags = '';

    /**
     * room
     *
     * @var \T3CS\T3csSessions\Domain\Model\Room
     */
    protected $room = null;

    /**
     * slot
     *
     * @var \T3CS\T3csSessions\Domain\Model\Slot
     */
    protected $slot = null;

    /**
     * @var string
     */
    protected $slideLink = '';

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the author
     *
     * @return string $author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the author
     *
     * @param string $author
     * @return void
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Returns the tags
     *
     * @return string $tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Sets the tags
     *
     * @param string $tags
     * @return void
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Returns the room
     *
     * @return \T3CS\T3csSessions\Domain\Model\Room $room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Sets the room
     *
     * @param \T3CS\T3csSessions\Domain\Model\Room $room
     * @return void
     */
    public function setRoom(\T3CS\T3csSessions\Domain\Model\Room $room)
    {
        $this->room = $room;
    }

    /**
     * Returns the slot
     *
     * @return \T3CS\T3csSessions\Domain\Model\Slot $slot
     */
    public function getSlot()
    {
        return $this->slot;
    }

    /**
     * Sets the slot
     *
     * @param \T3CS\T3csSessions\Domain\Model\Slot $slot
     * @return void
     */
    public function setSlot(\T3CS\T3csSessions\Domain\Model\Slot $slot)
    {
        $this->slot = $slot;
    }

    /**
     * Gets slideLink
     *
     * @return string
     */
    public function getSlideLink()
    {
        return $this->slideLink;
    }

    /**
     * Sets slideLink
     *
     * @param string $slideLink
     */
    public function setSlideLink($slideLink)
    {
        $this->slideLink = $slideLink;
    }
}