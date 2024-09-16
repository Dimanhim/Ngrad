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
            [['name'], 'string', 'max' => 255],
            [['_categories', '_cat_fields'], 'safe'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => 'Название',
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
     * @return string
     */
    public function getActionButtons($action = null)
    {
        if(!$action) return false;

        $str = '';

        if($action == 'view') {
            $str .= Html::a('<i class="bi bi-eye"></i>', ['product/view', 'id' => $this->id], ['class' => 'btn btn-xs btn-success']);
        }
        if($action == 'update') {
            $str .= Html::a('<i class="bi bi-pencil"></i>', ['product/update', 'id' => $this->id], ['class' => 'btn btn-xs btn-primary']);
        }
        if($action == 'delete') {
            $str .= Html::a('<i class="bi bi-trash"></i>', ['product/delete', 'id' => $this->id], ['class' => 'btn btn-xs btn-danger']);
        }

        return $str;
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
}
