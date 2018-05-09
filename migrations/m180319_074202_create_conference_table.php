<?php

use yii\db\Migration;
use app\entities\Conference;

/**
 * Handles the creation of table `conference`.
 */
class m180319_074202_create_conference_table extends Migration
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

        $this->createTable('conference', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'description' => $this->text()->notNull(),
            'start_time' => $this->integer()->notNull(),
            'end_time' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(Conference::STATUS_ACTIVE),
            'deleted' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conference');
    }
}
