<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_slot',
        'label' => 'begin',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,

        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'begin,end,',
        'iconfile' => 'EXT:t3cs_sessions/Resources/Public/Icons/tx_t3cssessions_domain_model_slot.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, begin, end, is_break',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden;;1, begin, end, is_break, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [

        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
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
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
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

        'begin' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_slot.begin',
            'config' => [
                'type' => 'input',
                'size' => 12,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0
            ],
        ],
        'end' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_slot.end',
            'config' => [
                'type' => 'input',
                'size' => 12,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0
            ],
        ],
        'is_break' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_slot.is_break',
            'config' => [
                'type' => 'check'
            ]
        ]
    ],
];
