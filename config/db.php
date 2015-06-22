<?php

return array_merge([
    'class' => 'yii\db\Connection',
    'charset' => 'utf8',
    'enableSchemaCache' => true,
], YII_ENV === 'dev' ? [
    'dsn' => 'pgsql:host=localhost;dbname=pdb',
    'username' => 'pdb',
    'password' => 'pdb',
] : (YII_ENV === 'test' ? [
    'dsn' => 'pgsql:host=localhost;port=5434;dbname=pdb',
    'username' => 'pdb',
    'password' => 'xo0Epees',
] : []));
