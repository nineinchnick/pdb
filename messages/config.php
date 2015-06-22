<?php

return [
    'sourcePath' => __DIR__ . DIRECTORY_SEPARATOR . '..',
    'languages' => ['pl'],
    'translator' => 'Yii::t',
    'sort' => true,
    'removeUnused' => false,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
        '/vendor/bin',
        '/vendor/bower',
        '/vendor/cebe',
        '/vendor/composer',
        '/vendor/ezyang',
        '/vendor/fzaninotto',
        '/vendor/hanneskod',
        '/vendor/hoa',
        '/vendor/mpdf',
        '/vendor/nikic',
        '/vendor/nineinchnick',
        '/vendor/phpspec',
        '/vendor/swiftmailer',
        '/vendor/symfony',
        '/vendor/yiisoft',
        '/vendor/netis/assortment',
        '/vendor/netis/erp',
        '/vendor/netis/orders',
        '/vendor/netis/shipments',
        '/vendor/netis/sync',
        '/vendor/netis/yii2-geo',
        '/vendor/netis/yii2-mail',
        '/vendor/netis/yii2-settings',
        '!/vendor/netis/yii2-utils',
    ],
    'format' => 'php',
    'messagePath' => __DIR__,
    'overwrite' => true,
    /*
    // 'db' output format is for saving messages to database.
    'format' => 'db',
    // Connection component to use. Optional.
    'db' => 'db',
    // Custom source message table. Optional.
    // 'sourceMessageTable' => '{{%source_message}}',
    // Custom name for translation message table. Optional.
    // 'messageTable' => '{{%message}}',
    */
];
