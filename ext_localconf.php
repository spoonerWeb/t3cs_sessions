<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'T3CS.' . $_EXTKEY,
    'Plan',
    [
        'Session' => 'list, listPast, new, create, edit, update, delete',
        'Room' => 'list',
        'Slot' => 'list',

    ],
    [
        'Session' => 'create, update, delete'
    ]
);

// Session device service
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['session_device'] = \T3CS\T3csSessions\Service\DeviceSessionService::class . '::processRequest';
