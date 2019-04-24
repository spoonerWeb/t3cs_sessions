<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_device',
        'label' => 'token',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => '',
        'iconfile' => 'EXT:t3cs_sessions/Resources/Public/Icons/tx_t3cssessions_domain_model_device.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, token, subscription_data',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden;;1, token, subscription_data'],
    ],
    'columns' => [

        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'token' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_device.token',
            'config' => [
                'type' => 'input',
                'renderType' => 'none'
            ],
        ],
        'subscription_data' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3cs_sessions/Resources/Private/Language/locallang_db.xlf:tx_t3cssessions_domain_model_device.subscription_data',
            'config' => [
                'type' => 'input',
                'renderType' => 'none'
            ]
        ],
    ],
];
