<?php

/* @var $this yii\web\View */
/* @var $model app\models\IngredientsForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $dishes array */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Ingredients;

$this->title = 'Тест';
?>
<div class="site-index">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>

    <?= $form->field($model, 'ingredients')->checkboxList(Ingredients::getIngredients()) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
    <?php if ($dishes): ?>
    
    <?php if (is_array($dishes)): ?>
    
    <h3>Вы выбрали:</h3>
    
    <ul>
        <?php foreach($dishes as $dish): ?>
        <li><?=$dish?></li>
        <?php endforeach; ?>
    </ul>
    
    <?php else: ?>
    
    <?=$dishes?>
    
    <?php endif; ?>
    
    <?php endif; ?>

</div>