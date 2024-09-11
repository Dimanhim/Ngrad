<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_attributes_relations".
 *
 * @property int $id
 * @property string $unique_id
 * @property string|null $product_id
 * @property int|null $product_attribute_id
 * @property float|null $qty
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class ProductAttributesRelation extends \app\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_attributes_relations}}';
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
        return [
            [['product_attribute_id'], 'integer'],
            [['product_id', 'qty'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::rules(), [
            'product_id' => 'Product ID',
            'product_attribute_id' => 'Product Attribute ID',
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
    public function getProductAttribute()
    {
        return $this->hasOne(ProductAttribute::className(), ['id' => 'product_attribute_id']);
    }
}
