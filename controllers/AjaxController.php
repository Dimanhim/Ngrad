<?php

namespace app\controllers;

use app\models\ProductAttribute;
use app\models\ProductAttributeCategory;
use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;

class AjaxController extends Controller
{
    public $_errors = [];
    public $_data = [
        'error' => 0,
        'message' => null,
        'data' => [],
    ];

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionGetProductCategoryField()
    {
        $productAttributeCategoryId = Yii::$app->request->post('product_category_id');
        if($productAttributeCategory = ProductAttributeCategory::findOne($productAttributeCategoryId)) {
            if($costItem = $productAttributeCategory->getCostItemHtml()) {
                $this->addData($costItem);
            }
        }
        return $this->response();

    }

    /**
     * @return mixed
     */
    public function actionGetAttributesCost()
    {
        $totalPrice = 0;
        $dataJson = Yii::$app->request->post('data');
        if($data = json_decode($dataJson, true)) {
            foreach($data as $values) {
                if($attribute = ProductAttribute::findOne($values['attribute_id'])) {
                    if($values['qty']) {
                        $totalPrice += $attribute->price * $values['qty'];
                    }
                }
            }
        }

        $this->addData($totalPrice);

        return $this->response();
    }















    /**
     * Добавляет текст первой ошибки из модели
     *
     * @param $model
     * @return bool
     */
    public function _addModelFirstError($model)
    {
        if($modelErrors = $model->errors) {
            foreach ($modelErrors as $modelAttributeName => $modelAttributeErrors) {
                if($modelAttributeErrors) {
                    foreach($modelAttributeErrors as $modelAttributeError) {
                        if($modelAttributeError) {
                            $this->_addError($modelAttributeError);
                            return false;
                        }

                    }
                }
            }
        }
    }

    public function addData($data)
    {
        $this->_data['data'] = $data;
    }

    public function _getErrors()
    {
        return $this->_errors;
    }

    protected function _hasErrors()
    {
        return !empty($this->_errors);
    }

    protected function _addError($message)
    {
        if($message) {
            $this->_errors[] = $message;
        }
    }

    protected function _addMessage($message) {
        if($message) {
            $this->_data['message'] = $message;
        }
    }

    protected function _errorSummary()
    {
        if($this->_errors) return implode(' ', $this->_errors);
        return false;
    }

    protected function response($data = [])
    {
        if(!$this->_hasErrors()) {
            if($data) {
                $this->_data['data'] = $data;
            }
        }
        else {
            $this->_data['error'] = 1;
            $this->_data['message'] = $this->_errorSummary();
        }

        $this->response->data = $this->_data;
        return $this->response->data;
    }
}
