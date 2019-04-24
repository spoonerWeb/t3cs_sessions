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


/**
 * Class IsPastViewHelper
 *
 * @package T3CS\T3csSessions\ViewHelpers
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class IsPastViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{

    public function initializeArguments()
    {
        $this->registerArgument('time', \DateTime::class, 'Time to check if it is in past', true);
    }

    public function render(): bool
    {
        $time = $this->arguments['time'];
        $now = new \DateTime('now');

        return $time < $now;
    }
}
