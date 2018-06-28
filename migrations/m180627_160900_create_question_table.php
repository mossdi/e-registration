<?php

use yii\db\Migration;

/**
 * Handles the creation of table `question`.
 */
class m180627_160900_create_question_table extends Migration
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

        $this->createTable('question', [
            'id' => $this->primaryKey(),
            'conference_id' => $this->integer()->notNull(),
            'question' => $this->string()->notNull(),
            'sort_order' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-question-conference_id',
            'question',
            'conference_id',
            'conference',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('question');

        $this->dropForeignKey(
            'fk-question-conference_id',
            'question'
        );
    }
}
