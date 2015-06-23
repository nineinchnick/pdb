<?php

use yii\bootstrap\Nav;

echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => Yii::$app->user->isGuest ? [] : [
        [
            'label' => Yii::t('app', 'Data exchange'),
            'url' => ['/sync'],
            //'visible' => Yii::$app->user->can('Sync.create'),
        ],
    ],
]);
