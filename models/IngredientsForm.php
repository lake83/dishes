<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Форма для выбора ингредиентов.
 */
class IngredientsForm extends Model
{
    public $ingredients;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['ingredients', 'required'],
            ['ingredients', function ($attribute, $params, $validator) {
                if (count($this->$attribute) < 2) {
                    $this->addError($attribute, 'Выберите больше ингредиентов.');
                } elseif (count($this->$attribute) > 5) {
                    $this->addError($attribute, 'Вы не можете выбрать более 5 ингредиентов.');
                } 
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ingredients' => 'Ингредиенты'
        ];
    }
    
    /**
     * Список блюд по переданым ингридиентам
     * 
     * @return array|boolean
     */
    public function getDishes()
    {
        $tbl_dish = Dish::tableName();
        $tbl_ingredients = Ingredients::tableName();
                
        $dishes = Dish::find()
            ->select($tbl_dish . '.name, COUNT(' . $tbl_ingredients . '.id) AS cnt, (SELECT COUNT(*) FROM ' . $tbl_ingredients . ' WHERE dish_id=' . $tbl_dish . '.id) AS ings')
            ->where([$tbl_dish . '.is_active' => 1])
            ->innerJoin($tbl_ingredients, $tbl_dish . '.id=dish_id AND ' . $tbl_ingredients . '.id IN (' . implode(',', $this->ingredients) . ')')
            ->groupBy($tbl_dish . '.name')
            ->orderBy(['cnt' => SORT_DESC])
            ->asArray()
            ->all();
                        
        if ($dishes) {
            $complete_dish = [];
            $not_complete_dish = [];
            foreach ($dishes as $dish) {
                if ($dish['cnt'] > 1) {
                    if ($dish['cnt'] == $dish['ings']) {
                        $complete_dish[] = $dish['name'];
                    } else {
                        $not_complete_dish[] = $dish['name'];
                    }
                }
            }
            if (!empty($complete_dish)) {
                return $complete_dish;
            }
            if (!empty($not_complete_dish)) {
                return $not_complete_dish;
            }
        }
        return false;
    }
}