<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Dish;

/* @var $this yii\web\View */
/* @var $model app\models\Ingredients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ingredients-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dish_id')->dropDownList(Dish::getDishes(), ['class' => 'form-control', 'prompt' => '- выбрать -']) ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
