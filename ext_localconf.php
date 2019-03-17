<?php
use T3CS\T3csSessions\Command\NotificationCommandController;
use T3CS\T3csSessions\Task\NotificationTask;

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'T3CS.' . $_EXTKEY,
	'Plan',
	array(
		'Session' => 'list, listPast, new, create, edit, update, delete',
		'Room' => 'list',
		'Slot' => 'list',

	),
	// non-cacheable actions
	array(
		'Session' => 'create, update, delete',
		'Room' => '',
		'Slot' => '',

	)
);

// Session device service
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['session_device'] = \T3CS\T3csSessions\Service\DeviceSessionService::class . '::processRequest';
