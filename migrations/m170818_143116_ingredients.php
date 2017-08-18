<?php

use yii\db\Migration;

/**
 * Class m170818_143116_ingredients
 */
class m170818_143116_ingredients extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('ingredients', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'dish_id' => $this->integer()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1)
        ], $tableOptions);
        
        $this->createIndex('idx-ingredients', 'ingredients', 'dish_id');
        $this->addForeignKey('ingredients_ibfk_1', 'ingredients', 'dish_id', 'dish', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('ingredients_ibfk_1', 'ingredients');
        $this->dropIndex('idx-ingredients', 'ingredients');
        
        $this->dropTable('ingredients');
    }
}
