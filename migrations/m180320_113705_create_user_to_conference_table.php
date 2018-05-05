<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_to_conference`.
 */
class m180320_113705_create_user_to_conference_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        } else {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';
        }

        $this->createTable('user_to_conference', [
            'user_id' => $this->integer()->notNull(),
            'conference_id' => $this->integer()->notNull(),
            'PRIMARY KEY (user_id, conference_id)'
        ], $tableOptions);

        $this->createIndex(
            'idx-user_to_conference_user_id-conference_id',
            'user_to_conference',
            'user_id, conference_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_to_conference');
    }
}
