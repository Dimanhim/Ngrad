<?php

namespace app\models;

use app\components\Helpers;
use app\models\traits\PurchaseTrait;
use Yii;

/**
 * This is the model class for table "purchases".
 *
 * @property int $id
 * @property string $unique_id
 * @property int|null $date_purchase
 * @property int|null $date_delivery
 * @property int|null $supplier_id
 * @property string|null $phone
 * @property int|null $status_id
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Purchase extends \app\models\BaseModel
{
    use PurchaseTrait;

    const STATUS_WAITING = 1;
    const STATUS_TAKE    = 2;

    public $_purchases;
    public $_price = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%purchases}}';
    }

    /**
     * @return string
     */
    public static function modelName()
    {
        return 'Закупки';
    }

    /**
     * @return int
     */
    public static function typeId()
    {
        return Gallery::TYPE_PURCHASES;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['supplier_id', 'status_id'], 'integer'],
            [['date_purchase', 'date_delivery', '_purchases', '_price'], 'safe'],
            [['phone'], 'string', 'max' => 255],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'date_purchase' => 'Дата закупки',
            'date_delivery' => 'Дата поставки',
            'supplier_id' => 'Поставщик',
            'phone' => 'Номер телефона',
            'status_id' => 'Статус',
        ]);
    }

    /**
     *
     */
    public function afterFind()
    {
        if($this->date_purchase) {
            $this->date_purchase = date('d.m.Y', $this->date_purchase);
        }
        if($this->date_delivery) {
            $this->date_delivery = date('d.m.Y', $this->date_delivery);
        }

        $this->setProductAttributes();
        $this->setPrice();

        return parent::afterFind();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if($this->date_purchase) {
            $this->date_purchase = strtotime($this->date_purchase);
        }
        if($this->date_delivery) {
            $this->date_delivery = strtotime($this->date_delivery);
        }
        if($this->phone) {
            $this->phone = Helpers::phoneFormat($this->phone);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->handleProductAttributes();

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(PurchaseProductAttribute::className(), ['purchase_id' => 'id'])->andWhere(['is_active' => 1, 'deleted' => null])->orderBy(['position' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    /**
     * @return array
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_WAITING => 'Ожидает',
            self::STATUS_TAKE    => 'Получен',
        ];
    }

    /**
     * @return mixed|null
     */
    public function getStatusName()
    {
        $statuses = self::getStatusesList();
        return $statuses[$this->status_id] ?? null;
    }

    public function getFormatPrice()
    {
        if(!$this->_price) return false;

        return number_format($this->_price, 0, '', ' ') . ' р.';
    }

    /**
     * @return int
     */
    public function getCountProducts()
    {
        $count = 0;

        if($productAttributes = $this->productAttributes) {
            foreach($productAttributes as $productAttribute) {
                $count += $productAttribute->qty;
            }
        }
        return $count;
    }

    /**
     * @return string
     */
    public function getPurchaseFieldHtml()
    {
        return Yii::$app->controller->renderPartial('//purchase/_table_purchases_item', [
            'attribute' => new PurchaseProductAttribute(),
        ]);
    }

    public function getPurchaseHeaderHtml()
    {
        return Yii::$app->controller->renderPartial('//purchase/_purchase_header', [
            'model' => $this,
        ]);
    }

    public function getPaTableList()
    {
        return Yii::$app->controller->renderPartial('//purchase/_table_purchases', [
            'model' => $this,
        ]);
    }
}
