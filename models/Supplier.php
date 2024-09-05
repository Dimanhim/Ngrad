<?php

namespace app\models;

use app\components\Helpers;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "stv_clients".
 *
 * Клиенты
 *
 */
class Supplier extends BaseModel
{
    const STATUS_ONE_TIME = 1;
    const STATUS_REGULAR  = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%suppliers}}';
    }

    /**
     * @return string
     */
    public static function modelName()
    {
        return 'Поставщики';
    }

    /**
     * @return int
     */
    public static function typeId()
    {
        return Gallery::TYPE_SUPPLIER;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['type', 'status_id'], 'integer'],
            [['email'], 'email'],
            [['name', 'phone', 'email', 'comment', 'user_id'], 'string', 'max' => 255],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ФИО',
            'phone' => 'Номер телефона',
            'email' => 'E-mail',
            'comment' => 'Комментарий',
            'type' => 'Тип',                    // Пока непонятно для чего
            'status_id' => 'Статус',
        ]);
    }

    public function beforeSave($insert)
    {
        if($this->phone) {
            $this->phone = Helpers::setPhoneFormat($this->phone);
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changetAttributes)
    {
        return parent::afterSave($insert, $changetAttributes);
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return [
            self::STATUS_ONE_TIME => 'Однократный',
            self::STATUS_REGULAR  => 'Постоянный',
        ];
    }
}

