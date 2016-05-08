<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_session',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,

        'versioningWS' => 2,
        'versioning_followPages' => true,

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,author,tags,room,slot,',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('t3cs_sessions') . 'Resources/Public/Icons/tx_t3cssessions_domain_model_session.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, author, tags, room, slot, slide_link',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title, author, tags, room, slot, slide_link, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [

        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0]
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_t3cssessions_domain_model_session',
                'foreign_table_where' => 'AND tx_t3cssessions_domain_model_session.pid=###CURRENT_PID### AND tx_t3cssessions_domain_model_session.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],

        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],

        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_session.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'author' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_session.author',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'tags' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_session.tags',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'room' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_session.room',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'tx_t3cssessions_domain_model_room',
                'foreign_table_where' => ' AND tx_t3cssessions_domain_model_room.pid = ###CURRENT_PID### ORDER BY name',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'slot' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_session.slot',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'tx_t3cssessions_domain_model_slot',
                'foreign_table_where' => ' AND tx_t3cssessions_domain_model_slot.pid = ###CURRENT_PID### ORDER BY begin ASC',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'slide_link' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_session.slide_link',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'wizards' => [
                    'link' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:header_link_formlabel',
                        'icon' => 'link_popup.gif',
                        'module' => [
                            'name' => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard'
                            ]
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ],
                'softref' => 'typolink',
            ],
        ],
    ],
];