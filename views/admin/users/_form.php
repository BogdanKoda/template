<?php

use app\components\Enums\Gender;
use app\models\Cities;
use app\models\extend\BaseModel;
use app\models\Roles;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use app\models\Users;

/** @var Users $model */
/** @var bool|null $isCreate */

$isCreate = $isCreate ?? false;

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'active')->checkbox() ?>

    <?php

    $selectedRoles = [];
    foreach ($model->roles ?? [] as $role) {
        $selectedRoles[$role->id] = ["selected" => true];
    }
    $cities = [];
    /** @var Cities $city */
    foreach (Cities::find()->orderBy("name ASC")->all() as $city) {
        $name = $city->name;
        $cities[$name] = $name;
    }

    echo $form->field($model, 'roles')->dropDownList(BaseModel::listRoles(Roles::find()->all()), ["multiple" => true, "options" => $selectedRoles]);
    echo $form->field($model, 'phoneNumber')->textInput();
    echo $form->field($model, 'email')->textInput();
    echo $form->field($model, 'nickname')->textInput();
    echo $form->field($model, 'firstName')->textInput();
    echo $form->field($model, 'lastName')->textInput();
    echo $form->field($model, 'patronymic')->textInput();
    if($isCreate) {
        echo $form->field($model, 'password')->textInput();
    }
    echo $form->field($model, 'city')->dropDownList($cities, ['prompt' => 'Не выбрано...']);
    echo $form->field($model, 'gender')->dropDownList(Gender::getConstantsByName(), ['prompt' => 'Не выбрано...']);
    echo $form->field($model, 'dateOfBirth')->widget(DatePicker::class, ["dateFormat" => "dd.MM.yyyy"]);
    echo $form->field($model, 'level')->textInput(["type" => "number", 'min' => 1, 'max' => 4]);
    echo $form->field($model, 'homeAddress')->textInput();
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
