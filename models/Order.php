<?php

namespace app\models;

use app\components\Helpers;
use app\models\traits\OrderTrait;
use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $unique_id
 * @property int|null $date_order
 * @property int|null $date_shipping
 * @property int|null $client_id
 * @property float|null $price
 * @property string|null $phone
 * @property int|null $status_id
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Order extends \app\models\BaseModel
{
    use OrderTrait;

    public $_purchases = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * @return string
     */
    public static function modelName()
    {
        return 'Заказы';
    }

    /**
     * @return int
     */
    public static function typeId()
    {
        return Gallery::TYPE_ORDER;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['client_id', 'status_id'], 'integer'],
            [['price'], 'number'],
            [['phone'], 'string', 'max' => 255],
            [['date_order', 'date_shipping'], 'safe'],
            [['_purchases'], 'safe'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер',
            'date_order' => 'Дата заказа',
            'date_shipping' => 'Дата отгрузки',
            'client_id' => 'Заказчик',
            'price' => 'Сумма',
            'phone' => 'Телефон',
            'status_id' => 'Статус',
            '_purchases' => 'Позиции заказа',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     *
     */
    public function afterFind()
    {
        if($this->date_order) {
            $this->date_order = date('d.m.Y', $this->date_order);
        }
        if($this->date_shipping) {
            $this->date_shipping = date('d.m.Y', $this->date_shipping);
        }

        $this->setPurchases();

        return parent::afterFind();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if($this->date_order) {
            $this->date_order = strtotime($this->date_order);
        }
        if($this->date_shipping) {
            $this->date_shipping = strtotime($this->date_shipping);
        }
        if($this->phone) {
            $this->phone = Helpers::phoneFormat($this->phone);
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->handlePurchases();

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchases()
    {
        return $this->hasMany(OrderPurchase::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     *
     */
    public function setPrice()
    {
        $fullPrice = 0;
        if($this->_purchases) {
            foreach($this->_purchases as $purchase) {
                if(isset($purchase['product_id']) and $purchase['product_id'] and ($product = Product::findOne($purchase['product_id']))) {
                    if($price = $product->fullCost) {
                        $fullPrice += $price * $purchase['count'];
                    }
                }
            }
        }
        $this->price = $fullPrice;
    }

    public function getPurchasesHtml()
    {
        return Yii::$app->controller->renderPartial('//order/_purchases', [
            'model' => $this,
        ]);
    }

    public function getFormatPrice()
    {
        $this->setPrice();

        return number_format($this->price, 0, '', ' ') . ' р.';
    }

    public function getCountProducts()
    {
        $count = 0;

        if($this->purchases) {
            foreach($this->purchases as $purchase) {
                $count += $purchase->qty;
            }
        }
        return $count;
    }
}
