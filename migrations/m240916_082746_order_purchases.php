<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240916_082746_order_purchases
 */
class m240916_082746_order_purchases extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_purchases}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'product_id'            => Schema::TYPE_INTEGER,
            'order_id'              => Schema::TYPE_INTEGER,
            'product_attribute_id'  => Schema::TYPE_INTEGER,
            'size_id'               => Schema::TYPE_INTEGER,
            'price'                 => Schema::TYPE_FLOAT,
            'qty'                   => Schema::TYPE_STRING,

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
        $this->dropTable('{{%order_purchases}}');
    }
}
