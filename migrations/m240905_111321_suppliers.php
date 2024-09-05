<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240905_111321_suppliers
 */
class m240905_111321_suppliers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%suppliers}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'name'                  => Schema::TYPE_STRING,
            'phone'                 => Schema::TYPE_STRING,
            'email'                 => Schema::TYPE_STRING,
            'comment'               => Schema::TYPE_STRING,
            'type'                  => Schema::TYPE_INTEGER . ' DEFAULT 1',
            'status_id'             => Schema::TYPE_INTEGER,
            'user_id'               => Schema::TYPE_STRING,

            'is_active'             => Schema::TYPE_SMALLINT . ' DEFAULT 1',
            'deleted'               => Schema::TYPE_SMALLINT,
            'position'              => Schema::TYPE_INTEGER,
            'created_at'            => Schema::TYPE_INTEGER,
            'updated_at'            => Schema::TYPE_INTEGER,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%suppliers}}');
    }
}
