<?php

use yii\db\Migration;

/**
 * Class m240917_193733_extend_products
 */
class m240917_193733_extend_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = 'ALTER TABLE products ADD model_name VARCHAR (255) DEFAULT NULL AFTER name';
        Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('products', 'model_name');
    }
}
