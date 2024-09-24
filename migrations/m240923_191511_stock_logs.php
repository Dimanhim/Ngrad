<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240923_191511_stock_logs
 */
class m240923_191511_stock_logs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%stock_logs}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'purchase_id'           => Schema::TYPE_INTEGER,
            'product_attribute_id'  => Schema::TYPE_INTEGER,
            'begin_qty'             => Schema::TYPE_INTEGER,
            'qty'                   => Schema::TYPE_INTEGER,

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
        $this->dropTable('{{%stock_logs}}');
    }


}
