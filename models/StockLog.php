<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_logs".
 *
 * @property int $id
 * @property string $unique_id
 * @property int|null $purchase_id
 * @property int|null $product_attribute_id
 * @property int|null $begin_qty
 * @property int|null $qty
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class StockLog extends \app\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%stock_logs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['purchase_id', 'product_attribute_id', 'begin_qty', 'qty'], 'integer'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'purchase_id' => 'Purchase ID',
            'product_attribute_id' => 'Product Attribute ID',
            'begin_qty' => 'Begin Qty',
            'qty' => 'Qty',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttribute()
    {
        return $this->hasOne(ProductAttribute::className(), ['id' => 'product_attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(Purchase::className(), ['id' => 'purchase_id']);
    }

    /**
     * @param null $purchaseId
     * @param null $productAttributeId
     * @param null $beginQty
     * @param null $qty
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function setLog($purchaseId = null, $productAttributeId = null, $beginQty = null, $qty = null)
    {
        if(!$purchaseId or !$productAttributeId or !$qty) return false;

        $model = new self();
        $model->purchase_id = $purchaseId;
        $model->product_attribute_id = $productAttributeId;
        $model->begin_qty = $beginQty;
        $model->qty = $qty;
        return $model->save();
    }
}
