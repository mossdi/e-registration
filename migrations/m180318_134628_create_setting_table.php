<?php

use yii\db\Migration;

/**
 * Handles the creation of table `setting`.
 */
class m180318_134628_create_setting_table extends Migration
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

        $this->createTable('setting', [
            'id' => $this->primaryKey(),
            'param' => $this->string()->notNull(),
            'value' => $this->text()->notNull(),
            'default' => $this->text()->notNull(),
            'label' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-setting-param',
            'setting',
            'param',
            true
        );

        $this->truncateTable('setting');

        $this->insert('setting', [
            'param' => 'registerOpen',
            'value' => '',
            'default' => '3600',
            'label' => 'Открытие регистрации',
            'type' => 'integer'
        ]);

        $this->insert('setting', [
            'param' => 'registerClose',
            'value' => '',
            'default' => '900',
            'label' => 'Закрытие регистрации',
            'type' => 'integer'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('setting');

        $this->dropIndex(
            'idx-setting-param',
            'setting'
        );
    }
}
