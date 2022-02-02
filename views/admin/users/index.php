<?php

use app\components\Strategy\Helpers;
use app\models\admin\UsersSearch;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php if (isset($groups['admin'])): ?>

        <form action="">
            <button name = "p" value="">Все</button>
            <button name = "p" value="86400">1 день</button>
            <button name = "p" value="604800">7 дней</button>
            <button name = "p" value="2592000">30 дней</button>
        </form>

    <?php endif; ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => "Лого",
                'format' => 'html',
                'value' => function($model) {
                    $url = $model->logo->url ?? Helpers::baseImage();
                    return Html::img($url, ["width" => 60]);
                }
            ],
            'firstName',
            'lastName',
            'phoneNumber',
            'email',
            'nickname',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>



</div>
