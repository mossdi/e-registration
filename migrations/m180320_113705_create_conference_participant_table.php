<?php

use yii\db\Migration;

/**
 * Handles the creation of table `conference_participant`.
 */
class m180320_113705_create_conference_participant_table extends Migration
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

        $this->createTable('conference_participant', [
            'user_id' => $this->integer()->notNull(),
            'conference_id' => $this->integer()->notNull(),
            'reseption_id' => $this->integer()->null(),
            'method' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'PRIMARY KEY (user_id, conference_id)'
        ], $tableOptions);

        $this->createIndex(
            'idx-conference_participant-user_id-conference_id',
            'conference_participant',
            'user_id, conference_id'
        );

        $this->addForeignKey(
            'fk-conference_participant-user_id',
            'conference_participant',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conference_participant');

        $this->dropIndex(
            'idx-conference_participant_user_id-conference_id',
            'conference_participant'
        );

        $this->dropForeignKey(
            'fk-conference_participant-user_id',
            'conference_participant'
        );
    }
}
