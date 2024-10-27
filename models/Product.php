<?php

namespace app\models;

use app\models\traits\ProductTrait;
use function PHPUnit\Framework\isReadable;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $unique_id
 * @property string|null $name
 * @property int|null $collection_id
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Product extends \app\models\BaseModel
{
    use ProductTrait;

    public $_categories = [];
    public $_cat_fields = ['attributes', 'values'];
    //public $_cost_values = [];
    public $_relations;

    public $maxAttributeCount = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * @return string
     */
    public static function modelName()
    {
        return 'Товары';
    }

    /**
     * @return int
     */
    public static function typeId()
    {
        return Gallery::TYPE_PRODUCT;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['collection_id'], 'integer'],
            [['name', 'model_name'], 'string', 'max' => 255],
            [['_categories', '_cat_fields', '_relations'], 'safe'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => 'Название',
            'model_name' => 'Модель',
            '_cat_fields' => 'Расходники',
            'collection_id' => 'Коллекция',
        ]);
    }

    public function init()
    {
        return parent::init();
    }

    /**
     *
     */
    public function afterFind()
    {
        $this->setProductRelations();
        return parent::afterFind();
    }

    public function beforeSave($insert)
    {
        $this->handleCollection();

        return parent::beforeSave($insert);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->handleAttributes();
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(ProductCollection::className(), ['id' => 'collection_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductRelations()
    {
        return $this->hasMany(ProductAttributesRelation::className(), ['product_id' => 'id'])->andWhere(['is_active' => 1, 'deleted' => null])->orderBy(['position' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['product_id' => 'id']);
    }

    public function getProductAttributeCategories()
    {
        if($this->_categories and $this->_categories['product_attribute_id'] and $this->_categories['values']) {
            $productAtributeIds = [];
            foreach($this->_categories['product_attribute_id'] as $productAttributeId) {
                $productAtributeIds[] = $productAttributeId;
            }

        }
        return ProductAttributeCategory::findModels()->all();
    }

    /**
     * @return float|int
     */
    public function getFullCost()
    {
        $totalPrice = 0;

        if(!$this->_categories) $this->setRelations();

        foreach($this->_categories as $categoryValues) {
            if(isset($categoryValues['product_attributes']) and $categoryValues['product_attributes']) {
                foreach($categoryValues['product_attributes'] as $attributeValues) {
                    $attribute = $attributeValues['product_attribute'];
                    $relation = $attributeValues['product_relation'];

                    if($attribute->price and $relation->qty) {
                        $totalPrice += $attribute->price * $relation->qty;
                    }
                }
            }
        }

        return $totalPrice;
    }

    public function getAttributesTableHtml()
    {
        return Yii::$app->controller->renderPartial('//product/_attribute_table', [
            'model' => $this,
        ]);
    }

    /**
     * @return array
     */
    public static function getPurchasesCount()
    {
        $items = 10;

        $data = [];
        for($i = 1; $i <= $items; $i++) {
            $data[$i] = $i;
        }

        return $data;
    }

    /**
     * @param array $totalCost
     * @return float|int|mixed
     */
    public static function getTotalCostFromAttributes(array $totalCost = [])
    {
        $cost = 0;

        if(!$totalCost) return $cost;

        foreach($totalCost as $attributeCost) {
            $cost += $attributeCost;
        }

        return round($cost) . ' руб.';
    }

    /*public function getAttributes()
    {
        return $this->hasMany()
    }*/

    /*public function getAttributeCategories()
    {
        return $this->hasMany()
    }*/

    /*public function getStock()
    {
        return $this->hasOne()
    }*/

    public function isColFilled()
    {
        return true;
    }

    public function categoriesFill($category)
    {

    }

    /**
     * @param $category
     * @param int $i
     * @return bool
     */
    public function getProductRelation($category, $i = 0)
    {
        if(
            $relations = $category->getProductRelations($this) and
            isset($relations[0]) and
            ($relation = $relations[$i])
        )
        {
            return $relation;
        }

        return false;
    }

    /**
     * @param $category
     * @return int
     * @throws \yii\db\Exception
     */
    public function getRowspan($category)
    {
        $sql = '
        SELECT COUNT(par.id) AS rowspan FROM product_attributes_relations AS par
            LEFT JOIN products AS p ON p.id = par.product_id
            LEFT JOIN product_attributes AS pa ON pa.id = par.product_attribute_id
            LEFT JOIN product_attribute_categories AS pac ON pac.id = pa.category_id
            WHERE p.id = ' . $this->id . ' AND pac.id = ' . $category->id;
        $query = Yii::$app->db->createCommand($sql)->queryAll();

        if(isset($query[0]['rowspan']) and $query[0]['rowspan'] > 3) return $query[0]['rowspan'];

        return 3;
    }
}
