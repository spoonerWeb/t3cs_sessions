<?php
namespace T3CS\T3csSessions\Domain\Repository;

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
 * Class SessionRepository
 *
 * @author Thomas Löffler <loeffler@spooner-web.de>
 */
class SessionRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @param integer $minutes
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findNextSessionsWithinMinutes($minutes = 0)
    {
        $query = $this->createQuery();
        $timeCheck = strtotime('+ ' . $minutes . ' minutes');

        $query->matching(
            $query->logicalAnd(
                [
                    $query->lessThanOrEqual('slot.begin', $timeCheck),
                    $query->greaterThan('slot.begin', time())
                ]
            )
        );

        return $query->execute();
    }

    /**
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findOnlySessions() {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('slot.isBreak', false)
        );

        return $query->execute();
    }
}
