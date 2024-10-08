<?php

namespace app\models\traits;

use app\models\OrderPurchase;
use app\models\Product;
use app\models\ProductSize;

trait OrderTrait
{
    /**
     * @return bool
     */
    public function setPurchases()
    {
        if($this->_purchases) return false;

        if($this->purchases) {
            foreach($this->purchases as $purchase) {
                if($size = ProductSize::findOne($purchase->size_id)) {
                    $this->_purchases[] = [
                        'product_id' => $purchase->product_id,
                        'size' => $size->name,
                        'count' => $purchase->qty,
                    ];
                }
            }
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    public function handlePurchases()
    {
        if($this->_purchases) {

            OrderPurchase::deleteAll(['order_id' => $this->id]);

            foreach($this->_purchases as $purchase) {
                $product = Product::findOne($purchase['product_id']);
                $size = ProductSize::findOne(['name' => $purchase['size']]);
                $qty = $purchase['count'];

                //\Yii::$app->infoLog->add('product_id', $purchase['product_id'], '--save-log.txt');
                //\Yii::$app->infoLog->add('product attributes', $product->attributes, '--save-log.txt');
                //\Yii::$app->infoLog->add('size attributes', $size->attributes, '--save-log.txt');
                //\Yii::$app->infoLog->add('size attributes', $size->attributes, '--save-log.txt');

                if($product and $size and $qty) {
                    $model = new OrderPurchase();
                    $model->order_id = $this->id;
                    $model->product_id = $product->id;
                    $model->size_id = $size->id;
                    $model->qty = $qty;
                    $model->save();
                }
            }
        }
    }
}
