<?php
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
