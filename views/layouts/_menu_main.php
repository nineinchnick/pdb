<?php

use yii\bootstrap\Nav;

echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => Yii::$app->user->isGuest ? [] : [
        [
            'label' => Yii::t('app', 'Orders'),
            'url' => ['/orders/orderItem'],
        ],
        [
            'label' => Yii::t('app', 'Shipments'),
            'url' => ['/shipments'],
        ],
        [
            'label' => Yii::t('app', 'Assortment'),
            'url' => ['/assortment'],
            //'visible' => Yii::$app->user->can('Products.update'),
            'items' => [
                [
                    'label' => Yii::t('app', 'Products'),
                    'url' => ['/assortment/product/index'],
                ],
                [
                    'label' => Yii::t('app', 'Categories'),
                    'url' => ['/assortment/category/index'],
                ],
                [
                    'label' => Yii::t('app', 'Product Document Types'),
                    'url' => ['/assortment/productDocumentType/index'],
                ],
            ],
        ],
        [
            'label' => Yii::t('app', 'Suppliers'),
            'url' => ['/contractors/contractor'],
            //'visible' => Yii::$app->user->can('Supplier.update'),
        ],
        [
            'label' => Yii::t('app', 'Data exchange'),
            'url' => ['/sync'],
            //'visible' => Yii::$app->user->can('Sync.create'),
        ],
        [
            'label' => Yii::t('app', 'Administration'),
            //'visible' => Yii::$app->user->can('Sync.create'),
            'items' => [
                [
                    'label' => Yii::t('app', 'Messages'),
                    'url' => ['/mail'],
                    //'visible' => Yii::$app->user->can('Message.create'),
                ],
                [
                    'label' => Yii::t('app', 'Settings'),
                    'url' => ['/settings'],
                    //'visible' => Yii::$app->user->can('Setting.create'),
                ],
                [
                    'label' => Yii::t('app', 'Users'),
                    'url' => ['/usr/manager'],
                    //'visible' => Yii::$app->user->can('usr.create'),
                ],
            ],
        ],
    ],
]);
