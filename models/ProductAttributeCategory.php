<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "product_attribute_categories".
 *
 * @property int $id
 * @property string $unique_id
 * @property string|null $name
 * @property int|null $type_id
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class ProductAttributeCategory extends \app\models\BaseModel
{
    const TYPE_QTY = 1;
    const TYPE_MP  = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_attribute_categories}}';
    }

    /**
     * @return string
     */
    public static function modelName()
    {
        return 'Категории расходников';
    }

    /**
     * @return int
     */
    public static function typeId()
    {
        return Gallery::TYPE_PRODUCT_ATTRIBUTE_CATEGORIES;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['type_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => 'Название',
            'type_id' => 'Тип измерения',
        ]);
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_QTY => 'Измерение - шт.',
            self::TYPE_MP  => 'Измерение - мп.'
        ];
    }

    /**
     * @return array
     */
    public static function getShortTypes()
    {
        return [
            self::TYPE_QTY => 'штук',
            self::TYPE_MP  => 'метраж'
        ];
    }

    public static function getSmallTypes()
    {
        return [
            self::TYPE_QTY => 'шт',
            self::TYPE_MP  => 'м'
        ];
    }

    /**
     * @return mixed|null
     */
    public function getTypeName()
    {
        $types = self::getTypes();
        return $types[$this->type_id] ?? null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['category_id' => 'id'])->andWhere(['is_active' => 1, 'deleted' => null])->orderBy(['position' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductRelations(Product $product = null)
    {
        if(!$product) return null;

        $data = [];

        if($relations = $product->productRelations) {
            foreach($relations as $relation) {
                if($relation->productAttribute and  ($category = $relation->productAttribute->category) and $category->id == $this->id) {
                    $data[] = $relation;
                }
            }
        }

        return $data;



        return $this->hasMany(ProductAttributesRelation::className(), ['product_attribute_id' => 'id'])
            ->viaTable('product_attributes', ['category_id' => 'id'])
            ->andWhere(['is_active' => 1, 'deleted' => null])->orderBy(['position' => SORT_ASC]);
    }



    /*public function getTeachers()
    {
        return $this->hasMany(Teacher::className(), ['id' => 'teacher_id'])
            ->viaTable(Yii::$app->db->tablePrefix.'course_teachers', ['course_id' => 'id']);
    }*/

    /**
     * @return array
     */
    public function getAttributeItems()
    {
        $data = [];
        if($productAttributes = $this->productAttributes) {
            foreach($productAttributes as $productAttribute) {
                $data[$productAttribute->id] = $productAttribute->name;
            }
        }
        return $data;
    }

    /**
     * @return mixed|null
     */
    public function getShortTypeText($short = false)
    {
        $shortTypes = $short ? self::getSmallTypes() : self::getShortTypes();
        return $shortTypes[$this->type_id] ?? null;
    }

    /**
     * @return string
     */
    public function getCostItemHtml()
    {
        return Yii::$app->controller->renderPartial('//product/_costs_items', [
            'model' => new Product(),
            'category' => $this,
            'attribute' => null,
            'relation' => null,
            'fixed' => false,
        ]);
    }
}
