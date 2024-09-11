<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240905_121457_product_attributes_relations
 */
class m240905_121457_product_attributes_relations extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_attributes_relations}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'product_id'            => Schema::TYPE_STRING,
            'product_attribute_id'  => Schema::TYPE_INTEGER,
            'qty'                   => Schema::TYPE_FLOAT,

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
        $this->dropTable('{{%product_attributes_relations}}');
    }
}
