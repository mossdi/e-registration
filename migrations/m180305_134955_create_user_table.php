<?php

use yii\db\Migration;
use app\entities\User;

/**
 * Handles the creation of table `user`.
 */
class m180305_134955_create_user_table extends Migration
{
    /**
     * @return bool|void
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        } else {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';
        }

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'last_name' => $this->string()->notNull(),
            'first_name' => $this->string()->notNull(),
            'patron_name' => $this->string()->notNull(),
            'organization' => $this->string()->notNull(),
            'post' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(User::STATUS_ACTIVE),
            'deleted' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
