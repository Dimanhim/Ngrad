<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_attributes".
 *
 * @property int $id
 * @property string $unique_id
 * @property string|null $name
 * @property string|null $alias
 * @property int|null $category_id
 * @property int|null $supplier_id
 * @property float|null $begin_qty
 * @property float|null $price
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class ProductAttribute extends \app\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_attributes}}';
    }

    /**
     * @return string
     */
    public static function modelName()
    {
        return 'Расходники';
    }

    /**
     * @return int
     */
    public static function typeId()
    {
        return Gallery::TYPE_PRODUCT_ATTRIBUTE;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['category_id', 'supplier_id'], 'integer'],
            [['begin_qty', 'price'], 'number'],
            [['name', 'alias'], 'string', 'max' => 255],
            //['alias', 'unique'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => 'Название',
            'alias' => 'Артикул',
            'category_id' => 'Категория',
            'supplier_id' => 'Поставщик',
            'begin_qty' => 'Начальное значение',
            'price' => 'Стоимость',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProductAttributeCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::className(), ['product_attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseAttributes()
    {
        return $this->hasMany(Purchase::className(), ['id' => 'product_attribute_id'])
            ->andWhere(['is_active' => 1])->andWhere(['not', ['deleted' => null]])->orderBy(['position' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getPurchases()
    {
        return $this->hasMany(Purchase::className(), ['id' => 'purchase_id'])
            ->viaTable('purchase_product_attributes', ['product_attribute_id' => 'id'])
            ->andWhere(['purchases.is_active' => 1])->andWhere(['not', ['purchases.deleted' => null]])->orderBy(['purchases.position' => SORT_ASC]);
    }
}
