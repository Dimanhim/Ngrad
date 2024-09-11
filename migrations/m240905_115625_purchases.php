<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240905_115625_purchases
 */
class m240905_115625_purchases extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%purchases}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'date_purchase'         => Schema::TYPE_INTEGER,
            'date_delivery'         => Schema::TYPE_INTEGER,
            'supplier_id'           => Schema::TYPE_INTEGER,
            'phone'                 => Schema::TYPE_STRING,
            'status_id'             => Schema::TYPE_SMALLINT  . ' DEFAULT 1',

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
        $this->dropTable('{{%purchases}}');
    }
}
