<?php
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $appAsset AppAsset */

$appAsset = AppAsset::register($this);
$brandLabel = Yii::$app->id;
$imagesPath = $appAsset->basePath.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR;
$imagesUrl = $appAsset->baseUrl.'/images/';
if (file_exists($imagesPath.'logo.png')) {
    $brandLabel = Html::img($imagesUrl.'logo.png', ['alt' => $brandLabel]);
}
$this->registerJs('if ($.pjax !== undefined) { $.pjax.defaults.timeout = 6000; }');
$this->registerJs('$(function () { $(\'[data-toggle="tooltip"]\').tooltip(); });');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
<?php if (file_exists($imagesPath.'favicon.png')): ?>
    <link rel="icon" type="image/png" href="<?= $imagesUrl; ?>favicon.png">
<?php endif; ?>
<?php if (file_exists($imagesPath.'favicon.ico')): ?>
    <link rel="icon" type="image/x-icon" href="<?= $imagesUrl; ?>favicon.ico">
<?php endif; ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => $brandLabel,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => ['class' => 'navbar navbar-fixed-top'],
                'innerContainerOptions' => ['class' => 'container-fluid'],
            ]);
            echo $this->render('_menu_main');
            echo $this->render('_menu_user');
            NavBar::end();
        ?>

        <div class="container-fluid">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>

            <div id="menu">
                <?= \yii\bootstrap\Nav::widget([
                    'items' => isset($this->params['menu']) ? $this->params['menu'] : [],
                    'options' => ['class' => 'nav nav-pills'],
                ]) ?>
            </div>

            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">
                &copy; copyright <?= date('Y') ?> <strong><?= isset(Yii::$app->params['copyright']) ? Yii::$app->params['copyright'] : '' ?></strong>
            </p>
            <p class="pull-right">
                <?= '';//Yii::powered() ?>
            </p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
