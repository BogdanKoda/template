<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$name = $model->firstName ?? $model->nickname ?? "users#".$model->id;

$this->title = 'Редактирование: ' . $name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['/admin/users']];
$this->params['breadcrumbs'][] = ['label' => $name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
