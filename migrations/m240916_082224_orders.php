<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240916_082224_orders
 */
class m240916_082224_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%orders}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'date_order'            => Schema::TYPE_INTEGER,
            'date_shipping'         => Schema::TYPE_INTEGER,
            'client_id'             => Schema::TYPE_INTEGER,
            'price'                 => Schema::TYPE_FLOAT,
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
        $this->dropTable('{{%orders}}');
    }
}
