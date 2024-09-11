<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_sizes".
 *
 * @property int $id
 * @property string $unique_id
 * @property string|null $name
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class ProductSize extends \app\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_sizes}}';
    }

    /**
     * @return string
     */
    public static function modelName()
    {
        return 'Размеры';
    }

    /**
     * @return int
     */
    public static function typeId()
    {
        return Gallery::TYPE_PRODUCT_SIZE;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
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
        ]);
    }
}
