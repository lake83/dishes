<?php

use yii\db\Migration;

/**
 * Class m170818_142547_dish
 */
class m170818_142547_dish extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('dish', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1)
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('dish');
    }
}
