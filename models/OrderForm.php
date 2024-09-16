<?php

namespace app\models;

use yii\base\Model;

class OrderForm extends Model
{
    public $price;
    public $supplier_id;
    public $supplier_name;
    public $data;

    public function rules()
    {
        return [
            [['price'], 'number'],
            [['supplier_id'], 'integer'],
            [['supplier_name'], 'string', 'max' => 255],
            [['data'], 'string'],
            //[['supplier_id', 'supplier_name'], 'validateSupplier', 'skipOnEmpty' => false]
            [['supplier_id'], 'required', 'when' => function($model) {
                return !$model->supplier_name;
            }, 'whenClient' => 'function (attribute, value) {
                return $("#orderform-supplier_name").val() == "";
            }', 'message' => 'Заполните заказчика'],
            [['supplier_name'], 'required', 'when' => function($model) {
                return !$model->supplier_id;
            }, 'whenClient' => 'function (attribute, value) {
                return $("#orderform-supplier_id").val() == "";
            }', 'message' => 'Заполните заказчика']
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateSupplier($attribute, $params)
    {
        if(!$this->supplier_name and !$this->supplier_id) {
            $this->addError($attribute, 'Необходимо указать поставщика');
        }
    }


}
