<?php

return [
    'authManager' => [
        'class' => 'netis\utils\rbac\DbManager',
        'cache' => 'cache',
    ],
    'db' => require(__DIR__ . '/db.php'),
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'i18n' => [
        'translations' => [
            '*' => [
                'class' => 'yii\i18n\PhpMessageSource',
            ],
        ],
    ],
    'formatter' => [
        'class' => 'netis\utils\web\Formatter',
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            'file' => [
                'class' => 'yii\log\FileTarget',
                'logVars' => ['_GET', '_SESSION', '_SERVER'],
            ],
            'db' => [
                'class' => 'netis\utils\log\DbTarget',
                'logTable' => '{{%log_request}}',
                'levels' => ['info'],
                'logVars' => [],
            ],
            'dberr' => [
                'class' => 'netis\utils\log\DbTarget',
                'logTable' => '{{%log_error}}',
                'levels' => ['error', 'warning'],
            ],
            'mail' => [
                'class' => 'yii\log\EmailTarget',
                'levels' => ['error', 'warning'],
                'except' => [
                    'yii\web\HttpException:404',
                ],
                'message' => [
                    'from' => ['log@pdb.com'],
                    'to' => ['jwas@netis.pl'],
                    'subject' => 'PDB '.(php_sapi_name() !== 'cli' ? 'Web' : 'Console') . ' app log',
                ],
            ],
        ],
    ],
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => YII_DEBUG,
        'messageConfig' => [
            'from' => 'noreply@pdb.com',
        ],
    ],
    'crudModelsMap' => [
        'class' => 'netis\utils\crud\ModelsMap',
    ],
];
