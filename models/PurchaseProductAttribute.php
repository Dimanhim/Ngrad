<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "purchase_product_attributes".
 *
 * @property int $id
 * @property string $unique_id
 * @property int|null $purchase_id
 * @property int|null $product_attribute_id
 * @property int|null $size_id
 * @property float|null $qty
 * @property float|null $total_price
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PurchaseProductAttribute extends \app\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%purchase_product_attributes}}';
    }

    /**
     * @return string
     */
    public static function modelName()
    {
        return '';
    }

    /**
     * @return int
     */
    public static function typeId()
    {
        return Gallery::TYPE_ANY;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['purchase_id', 'product_attribute_id', 'size_id'], 'integer'],
            [['qty', 'total_price'], 'number'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'purchase_id' => 'Закупка',
            'product_attribute_id' => 'Название материала',
            'size_id' => 'Размер',
            'Кол-во' => 'Qty',
            'Стоимость' => 'Total Price',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(ProductSize::className(), ['id' => 'size_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(Purchase::className(), ['id' => 'purchase_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttribute()
    {
        return $this->hasOne(ProductAttribute::className(), ['id' => 'product_attribute_id']);
    }
}
