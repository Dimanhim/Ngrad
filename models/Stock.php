<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property int $id
 * @property string $unique_id
 * @property int|null $product_attribute_id
 * @property float|null $qty
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Stock extends \app\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%stock}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['product_attribute_id'], 'integer'],
            [['qty'], 'number'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'product_attribute_id' => 'Product Attribute ID',
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
}
