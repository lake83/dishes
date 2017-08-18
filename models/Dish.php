<?php

namespace app\models;

use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%dish}}".
 *
 * @property int $id
 * @property string $name
 * @property int $is_active
 *
 * @property Ingredients[] $ingredients
 */
class Dish extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dish}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'is_active' => 'Активно',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert && $this->is_active == 1) {
            if ($models = $this->ingredients) {
                foreach ($models as $one) {
                    if ($one->is_active == 0) {
                        $model = Ingredients::findOne($one->id);
                        $model->is_active = 1;
                        $model->save();
                    }
                }
            }
        }
        TagDependency::invalidate(Yii::$app->cache, 'dish');
        
        return parent::afterSave($insert, $changedAttributes);
    }
    
    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        TagDependency::invalidate(Yii::$app->cache, 'dish');
        
        parent::afterDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngredients()
    {
        return $this->hasMany(Ingredients::className(), ['dish_id' => 'id']);
    }
    
    /**
     * Список блюд
     * 
     * @return array
     */
    public static function getDishes()
    {
        $db = Yii::$app->db;
        return $db->cache(function ($db) {
            return ArrayHelper::map(self::find()->all(), 'id', 'name');
        }, 0, new TagDependency(['tags' => 'dish']));
    }
}
