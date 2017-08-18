<?php

namespace app\models;

use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%ingredients}}".
 *
 * @property int $id
 * @property string $name
 * @property int $dish_id
 * @property int $is_active
 *
 * @property Dish $dish
 */
class Ingredients extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ingredients}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'dish_id'], 'required'],
            [['dish_id', 'is_active'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['dish_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dish::className(), 'targetAttribute' => ['dish_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'dish_id' => 'Блюдо',
            'is_active' => 'Активно',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert && $this->is_active == 0) {
            if (($model = $this->dish) && $model->is_active == 1) {
                $model->is_active = 0;
                $model->save();
            }
        }
        TagDependency::invalidate(Yii::$app->cache, 'ingredients');
        
        return parent::afterSave($insert, $changedAttributes);
    }
    
    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        TagDependency::invalidate(Yii::$app->cache, 'ingredients');
        
        parent::afterDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDish()
    {
        return $this->hasOne(Dish::className(), ['id' => 'dish_id']);
    }
    
    /**
     * Список активных ингредиентов
     * 
     * @return array
     */
    public static function getIngredients()
    {
        $db = Yii::$app->db;
        return $db->cache(function ($db) {
            return ArrayHelper::map(self::find()->where(['is_active' => 1])->all(), 'id', 'name');
        }, 0, new TagDependency(['tags' => 'ingredients']));
    }
}
