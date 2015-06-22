<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$components = require(__DIR__ . '/components.php');

return [
    'id' => 'pdb-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'language' => 'pl',
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'audit' => [
            'class' => 'nineinchnick\audit\console\AuditController',
        ],
        'migrate' => [
            //'class' => 'yii\console\controllers\MigrateController',
            'class' => 'dmstr\console\controllers\MigrateController',
            'templateFile' => '@app/migrations/template.php',
            'migrationLookup' => [
                '@vendor/netis/yii2-utils/log/migrations',
                '@vendor/netis/erp/migrations',
                '@vendor/netis/assortment/migrations',
            ],
        ],
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => array_merge($components, [
    ]),
    'params' => $params,
];
