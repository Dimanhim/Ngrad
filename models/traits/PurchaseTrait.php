<?php

namespace app\models\traits;

use app\models\OrderPurchase;
use app\models\Product;
use app\models\ProductAttribute;
use app\models\ProductSize;
use app\models\PurchaseProductAttribute;

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
}
