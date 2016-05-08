<?php
namespace T3CS\T3csSessions\ViewHelpers;

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

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class IsPastViewHelper
 *
 * @package T3CS\T3csSessions\ViewHelpers
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class IsPastViewHelper extends AbstractViewHelper
{

    /**
     * @param \DateTime $time
     * @return boolean
     */
    public function render(\DateTime $time)
    {
        if ($time->getTimestamp() < time()) {
            return true;
        }

        return false;
    }
}