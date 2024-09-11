<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240905_120247_purchase_product_attributes
 */
class m240905_120247_purchase_product_attributes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%purchase_product_attributes}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'purchase_id'           => Schema::TYPE_INTEGER,
            'product_attribute_id'  => Schema::TYPE_INTEGER,
            'size_id'               => Schema::TYPE_INTEGER,
            'qty'                   => Schema::TYPE_FLOAT,
            'total_price'           => Schema::TYPE_FLOAT,

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
        $this->dropTable('{{%purchase_product_attributes}}');
    }
}
