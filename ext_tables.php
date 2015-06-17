<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Plan',
	'Session Plan'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Sessions');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3cssessions_domain_model_session', 'EXT:t3cs_sessions/Resources/Private/Language/locallang_csh_tx_t3cssessions_domain_model_session.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3cssessions_domain_model_session');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3cssessions_domain_model_room', 'EXT:t3cs_sessions/Resources/Private/Language/locallang_csh_tx_t3cssessions_domain_model_room.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3cssessions_domain_model_room');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3cssessions_domain_model_slot', 'EXT:t3cs_sessions/Resources/Private/Language/locallang_csh_tx_t3cssessions_domain_model_slot.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3cssessions_domain_model_slot');
