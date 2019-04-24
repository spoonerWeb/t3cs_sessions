<?php
namespace T3CS\T3csSessions\Service;

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

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Exception;

class DeviceSessionService
{
    /**
     * @var string
     */
    protected $output = '';

    /**
     * @var string
     */
    protected $deviceToken = '';

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * Fetches the content and builds a content file out of it
     *
     * @param ServerRequestInterface $request the current request object
     * @param ResponseInterface $response the available response
     * @return ResponseInterface the modified response
     */
    public function processRequest(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;

        try {
            $this->deviceFunctions();
            $response->getBody()->write(json_encode($this->output));

            return $response;
        } catch (\InvalidArgumentException $e) {
            // add a 410 "gone" if invalid parameters given
            echo $e;

            return $response->withStatus(410);
        } catch (Exception $e) {
            echo $e;

            return $response->withStatus(404);
        }
    }

    /**
     * @return void
     * @throws \TYPO3\CMS\Core\Exception
     */
    public function deviceFunctions()
    {
        $method = $this->request->getQueryParams()['m'];
        $deviceToken = $this->request->getQueryParams()['d'];
        if (empty($deviceToken) || $deviceToken === 'undefined') {
            throw new \InvalidArgumentException('No device token given!');
        }
        $this->deviceToken = $deviceToken;
        switch ($method) {
            case 'setSessionsForDevice':
                $sessionIdList = json_decode($this->request->getQueryParams()['s']);
                if (!is_array($sessionIdList)) {
                    throw new \InvalidArgumentException('Session list must be an array!');
                }
                $this->output = $this->setSessionsForDevice($sessionIdList);
                break;
            case 'getAllSessionsForDevice':
                $this->output = $this->getAllSessionsForDevice();
                break;
            case 'subscribe':
                $subscriptionData = json_decode($this->request->getBody()->getContents(), true);
                if (empty($subscriptionData)) {
                    throw new \InvalidArgumentException('No subscription data was provided!');
                }
                $this->output = $this->subscribe($subscriptionData);
                break;
            case 'unsubscribe':
                $this->output = $this->unsubscribe();
                break;
            default:
                throw new Exception('Invalid method!');
        }
    }

    /**
     * @param array $sessionIdList
     * @return bool
     */
    public function setSessionsForDevice(array $sessionIdList)
    {
        $deviceRecordUid = $this->getUidOfDeviceToken();
        $sessionIdList = array_filter($sessionIdList, function ($id) {
            return (int)$id;
        });
        $multipleRowsToInsert = [];
        foreach ($sessionIdList as $id) {
            $multipleRowsToInsert[] = [
                $deviceRecordUid,
                $id
            ];
        }
        $deviceSessionTable = 'tx_t3cssessions_device_session_mm';
        $connection = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getConnectionForTable($deviceSessionTable);
        $success = (bool)$connection->bulkInsert($deviceSessionTable, $multipleRowsToInsert, ['uid_local', 'uid_foreign']);

        return $success;
    }

    /**
     * @return int
     */
    private function getUidOfDeviceToken()
    {
        $deviceTable = 'tx_t3cssessions_domain_model_device';
        $deviceToken = $this->getDatabaseConnection()->quoteStr($this->deviceToken, $deviceTable);
        $deviceRecord = $this->getDatabaseConnection()
            ->exec_SELECTgetSingleRow('uid', $deviceTable, 'token = "' . $deviceToken . '" AND NOT deleted AND NOT hidden');

        if (empty($deviceRecord)) {
            $this->getDatabaseConnection()->exec_INSERTquery($deviceTable, [
                    'token' => $deviceToken,
                    'tstamp' => time(),
                    'crdate' => time()
                ]);
            $deviceUid = $this->getDatabaseConnection()->sql_insert_id();
        } else {
            $deviceUid = $deviceRecord['uid'];
        }

        return $deviceUid;
    }

    /**
     * @return array
     */
    public function getAllSessionsForDevice()
    {
        $deviceRecordUid = $this->getUidOfDeviceToken();
        $deviceSessionTable = 'tx_t3cssessions_device_session_mm';
        $result = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getConnectionForTable($deviceSessionTable)
            ->select(['sessions'], $deviceSessionTable, ['uid_local = ' . $deviceRecordUid])
            ->execute();

        return array_column($result, 'sessions');
    }

    /**
     * @param array $subscriptionData
     * @return bool
     */
    public function subscribe(array $subscriptionData)
    {
        $deviceRecordUid = $this->getUidOfDeviceToken();
        $update = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getConnectionForTable('tx_t3cssessions_domain_model_device')
            ->update(
                'tx_t3cssessions_domain_model_device',
                [
                    'subscription_data' => serialize($subscriptionData),
                    'tstamp' => time()
                ],
                [
                    'uid = ' . $deviceRecordUid
                ]
        );

        return (bool)$update;
    }

    /**
     * @return bool
     */
    public function unsubscribe()
    {
        $deviceRecordUid = $this->getUidOfDeviceToken();

        $update = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getConnectionForTable('tx_t3cssessions_domain_model_device')
            ->update(
                'tx_t3cssessions_domain_model_device',
                [
                    'subscription_data' => '',
                    'tstamp' => time()
                ],
                [
                    'uid = ' . $deviceRecordUid
                ]
        );

        return (bool)$update;
    }
}

