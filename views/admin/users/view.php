<?php

use app\components\Enums\Gender;
use app\components\Strategy\Helpers;
use app\models\extend\BaseModel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\admin\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = $searchModel->firstName ?? $searchModel->nickname ?? "users#".$searchModel->id;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['/admin/users']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $searchModel->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $searchModel->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить пользователя? Действие не отменить!',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $searchModel,
        'attributes' => [
            'id',
            [
                "label" => "Аватар",
                "format" => "html",
                "value" => Html::img($searchModel->logo->url ?? Helpers::baseImage(), ["width" => 150])
            ],
            "phoneNumber",
            "email",
            "nickname",
            'firstName',
            'lastName',
            'patronymic',
            "city",
            [
                "label" => "Гендер",
                "value" => $searchModel->gender ? Gender::getValueByName($searchModel->gender) : null
            ],
            [
                "label" => "Дата рождения",
                "value" => $searchModel->dateOfBirth ? date("d.m.Y", $searchModel->dateOfBirth) : null
            ],
            "level",
            "homeAddress",
            [
                "label" => "Дата создания",
                "value" => $searchModel->createdAt ? date("H:i:s d.m.Y", $searchModel->createdAt) : null
            ],
            [
                "label" => "Дата обновления",
                "value" => $searchModel->updatedAt ? date("H:i:s d.m.Y", $searchModel->updatedAt) : null
            ],
            [
                "label" => $searchModel->attributeLabels()["lastLoginAt"],
                "value" => $searchModel->lastLoginAt ? date("H:i:s d.m.Y", $searchModel->lastLoginAt) : null
            ],
            [
                "label" => "Роли",
                "value" => implode(", ", BaseModel::parseRoles($searchModel->roles, "name"))
            ],
            [
                "label" => $searchModel->attributeLabels()["active"],
                "value" => $searchModel->active ? "Да" : "Нет",
            ],
        ],
    ]) ?>

</div>

