<?php

namespace app\models\traits;

use app\models\Order;
use app\models\OrderPurchase;
use app\models\Product;
use app\models\ProductSize;
use app\models\Stock;

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
            if($orderPurchases = OrderPurchase::findModels()->andWhere(['order_id' => $this->id])->all()) {
                foreach($orderPurchases as $orderPurchase) {
                    $orderPurchase->deleteModel(true);
                }
            }

            if($this->isDeleted()) return false;

            foreach($this->_purchases as $purchase) {
                $product = Product::findOne($purchase['product_id']);
                $size = ProductSize::findOne(['name' => $purchase['size']]);
                $qty = $purchase['count'];

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

    /**
     * @throws \yii\db\Exception
     */
    public function setPurchasesToStock($qty)
    {
        if(!$model = Order::findOne($this->id)) return false;

        if($model->_purchases) {
            foreach($model->_purchases as $orderPurchase) {
                if($product = Product::findOne($orderPurchase['product_id'])) {
                    if($product->_relations) {
                        foreach($product->_relations as $relation) {
                            if(!$stock = Stock::findOne(['product_attribute_id' => $relation->product_attribute_id])) {
                                $stock = new Stock();
                                $stock->product_attribute_id = $relation->product_attribute_id;
                                $stock->qty = $stock->productAttribute->begin_qty ?? 0;
                            }
                            if($qty < 0) {
                                $stock->qty -= $relation->qty * $orderPurchase['count'];
                            }

                            $stock->save();
                        }
                    }
                }
            }
        }
    }
}
