<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

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

    /**
     * @return string
     */
    public function getPriceText()
    {
        return $this->price . ' ' . ($this->category->getShortTypeText(true) ?? null);
    }

    /**
     * @return array
     */
    public static function getList($categoryId = null)
    {
        if($categoryId) {
            return ArrayHelper::map(self::findModels()->andWhere(['category_id' => $categoryId])->andWhere(['not', ['name' => null]])->asArray()->all(), 'id', 'name');
        }

        return ArrayHelper::map(self::findModels()->andWhere(['not', ['name' => null]])->asArray()->all(), 'id', 'name');
    }

    /**
     * @return int
     */
    public function getStockValue()
    {
        if($this->stock) {
            return $this->stock->qty;
        }

        return $this->begin_qty;
    }

    /**
     * @return int
     */
    public function getWaitValue()
    {
        $sql = '
            SELECT SUM(purchase_product_attributes.qty) as wait_count, product_attributes.name as attribute_name, product_attributes.id as attribute_id FROM purchase_product_attributes
            LEFT JOIN purchases ON purchases.id = purchase_product_attributes.purchase_id
            LEFT JOIN product_attributes ON product_attributes.id = purchase_product_attributes.product_attribute_id
            WHERE product_attributes.id = ' . $this->id . ' AND purchases.status_id = ' . Purchase::STATUS_WAITING . '
            ';
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        if($query and isset($query[0]) and isset($query[0]['wait_count'])) {
            return $query[0]['wait_count'];
        }

        return 0;
    }
}
