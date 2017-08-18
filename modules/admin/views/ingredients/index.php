<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Dish;
/* @var $this yii\web\View */
/* @var $searchModel app\models\IngredientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ингредиенты';
?>
<div class="ingredients-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Создать ингредиент', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'dish_id',
                'filter' => Html::activeDropDownList($searchModel, 'dish_id', Dish::getDishes(), ['class' => 'form-control', 'prompt' => '- выбрать -']),
                'value' => function ($model, $index, $widget) {
                    return Dish::getDishes()[$model->dish_id];}
            ],
            'is_active:boolean',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'options' => ['width' => '50px']
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
