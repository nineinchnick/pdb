<?php

$params = require(__DIR__ . '/params.php');
$components = require(__DIR__ . '/components.php');

// name of the deployment configuration set
$themeName = 'pdb';

$config = [
    'id' => 'pdb',
    'aliases' => [
        '@nineinchnick/usr' => '@vendor/nineinchnick/yii2-usr',
        '@netis' => '@vendor/netis',
    ],
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        [
            'class' => 'yii\filters\ContentNegotiator',
            'languages' => [
                'en',
                'pl',
            ],
        ],
        'utils',
        'assortment',
        'sync',
    ],
    'components' => array_merge($components, [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'hZGAWNy5B0BJdwsO5WeQq0OPUOzGQhu3',
        ],
        'response' => [
            'formatters' => [
                'csv' => 'netis\utils\web\CsvResponseFormatter',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'loginUrl' => ['usr/login'],
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'linkAssets' => YII_DEBUG,
            'bundles' => [
                'app\assets\AppAsset' => [
                    'sourcePath' => "@app/assets/app",
                ],
            ],
        ],
        'view' => [
            'class' => 'netis\utils\web\View',
            'theme' => [
                'pathMap' => [
                    '@app/views' => "@app/themes/$themeName",
                    '@app/vendor/nineinchnick/yii2-usr/views' => "@app/themes/$themeName/usr",
                ],
                'baseUrl' => "@web/themes/$themeName",
            ],
        ],
    ]),
    'modules' => [
        'usr' => [
            'class' => 'nineinchnick\usr\Module',
            'controllerMap' => [
                'manager' => [
                    'class' => 'nineinchnick\usr\controllers\ManagerController',
                    'layout' => '//main',
                ],
            ],
        ],
        'utils' => [
            'class' => 'netis\erp\Module',
        ],
        'assortment' => [
            'class' => 'netis\assortment\Module',
        ],
        'sync' => [
            'class' => 'nineinchnick\sync\Module',
        ],
    ],
    'params' => $params,
];

if (YII_ENV != 'prod') {
    // configuration adjustments for 'dev' environment
    $allowedIps = ['192.168.*', '127.0.0.1', '::1'];

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => $allowedIps,
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => $allowedIps,
        'generators' => [
            'netisModel' => [
                'class' => 'netis\utils\generators\model\Generator',
            ]
        ],
    ];
}

return $config;
