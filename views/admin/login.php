<?php

use app\models\admin\Login;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var Login $model */

?>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-t-50 p-b-90">
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => '<div class="wrap-input100 validate-input m-b-16" data-validate = "">{input}<span class="focus-input100"></span></div>{error}',
                ]
            ]); ?>
            <span class="login100-form-title p-b-51">Вход</span>

            <?=$form->field($model, 'phoneNumber')->textInput(['autofocus' => true, 'class' => 'input100', 'placeholder' => 'Номер телефона'])->label(false) ?>
            <?=$form->field($model, 'password')->passwordInput(['class' => 'input100', 'placeholder' => 'Пароль'])->label(false) ?>
            <?= Yii::$app->session->getFlash('error'); ?>

            <div class="container-login100-form-btn m-t-17">
                <?=Html::submitButton('войти', ['class'=> 'login100-form-btn']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
