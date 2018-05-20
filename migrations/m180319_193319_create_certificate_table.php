<?php

use yii\db\Migration;
use app\entities\Certificate;

/**
 * Handles the creation of table `certificate`.
 */
class m180319_193319_create_certificate_table extends Migration
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

        $this->createTable('certificate', [
            'id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'conference_id' => $this->integer()->notNull(),
            'date_issue' => $this->integer()->null(),
            'document_series' => $this->string()->null(),
            'learning_method' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(Certificate::STATUS_ACTIVE),
            'deleted' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'PRIMARY KEY (id, user_id, conference_id)'
        ], $tableOptions);

        $this->createIndex(
            'idx-certificate-id-user_id-conference_id',
            'certificate',
            'user_id, conference_id, id',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('certificate');

        $this->dropIndex(
            'idx-certificate-id-user_id-conference_id',
            'certificate'
        );
    }
}
