<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 */
use yii\db\Migration;

class m160614_141211_profile extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->unique()->notNull(),
            'p_username' => $this->string(),
            'birthday' => $this->date(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
        ], $tableOptions);

    }

    public function down()
    {
        echo "m160614_141211_profile cannot be reverted.\n";

        return false;
    }

}
