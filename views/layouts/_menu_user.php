<?php

use yii\bootstrap\Nav;

$routeMap = [
    'usr/default/login' => 'usr/login',
    'usr/default/logout' => 'usr/logout',
    'usr/default/profile' => 'usr/profile',
];

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'route' => isset($routeMap[$route = Yii::$app->controller->getRoute()]) ? $routeMap[$route] : null,
    'items' => [
        ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
        ['label' => Yii::t('app', 'Contact'), 'url' => ['/site/contact']],
        [
            'label' => Yii::t('app', 'Login'),
            'url' => ['/usr/login'],
            'visible' => Yii::$app->user->isGuest,
        ],
        [
            'label' => Yii::t('app', 'Profile'),
            'url' => ['/usr/profile'],
            'visible' => !Yii::$app->user->isGuest,
        ],
        [
            'label' => Yii::t('app', 'Logout').' ('
                . (Yii::$app->user->identity === null ? '' : Yii::$app->user->identity->username) . ')',
            'url' => ['/usr/logout'],
            'visible' => !Yii::$app->user->isGuest,
            'linkOptions' => ['data-method' => 'post'],
        ],
    ],
]);
