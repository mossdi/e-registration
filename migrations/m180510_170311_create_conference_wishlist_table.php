<?php

use yii\db\Migration;

/**
 * Handles the creation of table `conference_wishlist`.
 */
class m180510_170311_create_conference_wishlist_table extends Migration
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

        $this->createTable('conference_wishlist', [
            'user_id' => $this->integer()->notNull(),
            'conference_id' => $this->integer()->notNull(),
            'PRIMARY KEY (user_id, conference_id)'
        ], $tableOptions);

        $this->createIndex(
            'idx-conference_wishlist-conference_id',
            'conference_wishlist',
            'user_id, conference_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conference_wishlist');

        $this->dropIndex(
            'idx-conference_wishlist-conference_id',
            'conference_wishlist'
        );
    }
}
