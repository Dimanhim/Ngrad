<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_purchases".
 *
 * @property int $id
 * @property string $unique_id
 * @property int|null $product_id
 * @property int|null $order_id
 * @property int|null $product_attribute_id
 * @property int|null $size_id
 * @property float|null $price
 * @property string|null $qty
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class OrderPurchase extends \app\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_purchases}}';
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
            [['product_id', 'order_id', 'product_attribute_id', 'size_id'], 'integer'],
            [['price'], 'number'],
            [['qty'], 'string', 'max' => 255],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'product_id' => 'Product ID',
            'order_id' => 'Order ID',
            'product_attribute_id' => 'Product Attribute ID',
            'size_id' => 'Size ID',
            'price' => 'Price',
            'qty' => 'Qty',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
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
    public function getSize()
    {
        return $this->hasOne(ProductSize::className(), ['id' => 'size_id']);
    }
}
