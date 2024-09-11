<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240905_114725_product_attributes
 */
class m240905_114725_product_attributes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_attributes}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'name'                  => Schema::TYPE_STRING,
            'alias'                 => Schema::TYPE_STRING,
            'category_id'           => Schema::TYPE_INTEGER,
            'supplier_id'           => Schema::TYPE_INTEGER,
            'begin_qty'             => Schema::TYPE_FLOAT,
            'price'                 => Schema::TYPE_FLOAT,

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
        $this->dropTable('{{%product_attributes}}');
    }
}
