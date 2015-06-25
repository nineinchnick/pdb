<?php

use nineinchnick\usr\components;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var nineinchnick\usr\models\ProfileForm $model
 * @var nineinchnick\usr\models\PasswordForm $passwordForm
 * @var ActiveForm $form
 */

\yii\widgets\PjaxAsset::register($this);
\netis\utils\widgets\FormBuilder::registerSelect($this);
?>

        <div class="row">
            <div class="col-md-4">
            <?= $form->field($model, 'username', [
                'inputOptions' => [
                    'autofocus' => true, 'class' => 'form-control'
                ],
            ]) ?>
            <?= $form->field($model, 'email') ?>
            </div>
            <div class="col-md-4">
            <?= $form->field($model, 'firstName') ?>
            <?= $form->field($model, 'lastName') ?>
            </div>
            <div class="col-md-4">

                <?php if ($model->scenario !== 'register'): ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                <?php endif; ?>

                <?= $this->render('/default/_newpassword', [
                    'form' => $form,
                    'model' => $passwordForm,
                    'focus' => false,
                ], $this->context) ?>

            </div>
        </div>

<?php if ($model->getIdentity() instanceof \nineinchnick\usr\components\PictureIdentityInterface && !empty($model->pictureUploadRules)):
    $picture = $model->getIdentity()->getPictureUrl(80, 80);
    if ($picture !== false) {
        $picture['alt'] = Yii::t('usr', 'Profile picture');
        $url = $picture['url'];
        unset($picture['url']);
    }
    ?>
    <?= $picture === false ? '' : Html::img($url, $picture); ?>
    <?= $form->field($model, 'picture')->fileInput() ?>
    <?= $form->field($model, 'removePicture')->checkbox() ?>
<?php endif; ?>
