<?php

namespace app\models\traits;

use app\models\OrderPurchase;
use app\models\Product;
use app\models\ProductAttribute;
use app\models\ProductSize;
use app\models\PurchaseProductAttribute;
use app\models\Stock;
use app\models\StockLog;

trait PurchaseTrait
{
    /**
     * @return bool
     */
    public function setProductAttributes()
    {
        if($this->_purchases) return false;

        if($this->productAttributes) {
            foreach($this->productAttributes as $attribute) {
                $this->_purchases[] = [
                    'category_id' => $attribute->productAttribute->category->id,
                    'attribute_id' => $attribute->product_attribute_id,
                    'val' => $attribute->qty,
                ];
            }
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    public function handleProductAttributes()
    {
        PurchaseProductAttribute::deleteAll(['purchase_id' => $this->id]);

        if($this->_purchases) {
            foreach($this->_purchases as $purchase) {
                $model = new PurchaseProductAttribute();
                $model->purchase_id = $this->id;
                $model->product_attribute_id = $purchase['attribute_id'];
                $model->qty = $purchase['val'];
                $model->save();
            }
        }
    }

    /**
     *
     */
    public function setPrice()
    {
        if($this->_purchases) {
            foreach($this->_purchases as $purchase) {
                if(isset($purchase['attribute_id']) and $purchase['attribute_id'] and ($productAttribute = ProductAttribute::findOne($purchase['attribute_id']))) {
                    if($price = $productAttribute->price) {
                        $this->_price += $price * $purchase['val'];
                    }
                }
            }
        }
    }

    public function setAttributesToStock()
    {
        if($this->status_id != self::STATUS_TAKE) return false;

        if($purchaseProductAttributes = $this->productAttributes) {
            foreach($purchaseProductAttributes as $purchaseProductAttribute) {
                if($productAttribute = $purchaseProductAttribute->productAttribute) {

                    if(StockLog::find()->where(['purchase_id' => $this->id, 'product_attribute_id' => $productAttribute->id])->exists()) continue;

                    if(!$stock = Stock::findOne(['product_attribute_id' => $productAttribute->id])) {
                        $stock = new Stock();
                        $stock->product_attribute_id = $productAttribute->id;
                        $stock->qty = $productAttribute->begin_qty;
                    }
                    $beginQty = $stock->qty;
                    $stock->qty += $purchaseProductAttribute->qty;
                    if($stock->save()) {
                        StockLog::setLog($this->id, $productAttribute->id, $beginQty, $stock->qty);
                    }
                }
            }
        }
    }
}
