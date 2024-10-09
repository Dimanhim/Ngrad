<?php

namespace app\controllers;

use app\components\Helpers;
use app\models\Order;
use app\models\OrderPurchase;
use app\models\Product;
use app\models\ProductAttribute;
use app\models\ProductAttributeCategory;
use app\models\ProductSize;
use app\models\Purchase;
use app\models\PurchaseProductAttribute;
use PHPUnit\TextUI\Help;
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
                        $totalPrice += $attribute->price * Helpers::prepareFloat($values['qty']);
                    }
                }
            }
        }

        $this->addData($totalPrice);

        return $this->response();
    }

    /**
     * @return bool|mixed
     */
    public function actionCalculateOrder()
    {
        $totalPrice = 0;
        $data = Yii::$app->request->post('data');

        if(!$data) {
            return false;
        }

        foreach($data as $values) {
            if($product = Product::findOne($values['product_id'])) {
                $cost = $product->fullCost;
                if($values['count']) {
                    $totalPrice += $cost * $values['count'];
                }
            }
        }
        $this->addData([$totalPrice]);

        return $this->response();
    }

    public function actionCalculatePurchase()
    {
        $totalPrice = 0;
        $data = Yii::$app->request->post('data');

        \Yii::$app->infoLog->add('data', $data);

        if(!$data) {
            return false;
        }

        foreach($data as $items) {
            $category = ProductAttributeCategory::findOne($items['category_id']);
            $attribute = ProductAttribute::findOne($items['attribute_id']);
            if($attribute and $items['val']) {
                $totalPrice += $attribute->price * $items['val'];
            }
        }

        return $this->response($totalPrice);




        /*foreach($data as $values) {
            if($product = Product::findOne($values['product_id'])) {
                $cost = $product->fullCost;
                if($values['count']) {
                    $totalPrice += $cost * $values['count'];
                }
            }
        }
        $this->addData([$totalPrice]);*/

        return $this->response();
    }

    /**
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionChangeOrderDateShipping()
    {
        $order_id = Yii::$app->request->post('order_id');
        $date = Yii::$app->request->post('date');
        if($date and ($order = Order::findOne($order_id))) {
            $order->date_shipping = $date;
            if(!$order->save()) {
                $this->_addError('Произошла ошибка обновления даты, пожалуйста, попробуйте позднее');
            }
            return $this->response();
        }
        else {
            $this->_addError('Произошла неизвестная ошибка, пожалуйста, попробуйте позднее');
        }

        return $this->response();
    }

    /**
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionChangePurchaseDateDelivery()
    {
        $purchase_id = Yii::$app->request->post('purchase_id');
        $date = Yii::$app->request->post('date');
        if($date and ($purchase = Purchase::findOne($purchase_id))) {

            if($date <= $purchase->date_purchase) {
                $this->_addError('Дата поставки не может быть меньше даты закупки');
                return $this->response();
            }
            $deliveryBegin = $purchase->date_delivery;
            $purchase->date_delivery = $date;
            if(!$deliveryBegin) {
                $purchase->status_id = Purchase::STATUS_TAKE;
            }
            if(!$purchase->save()) {
                $this->_addError('Произошла ошибка обновления даты, пожалуйста, попробуйте позднее');
            }
            return $this->response();
        }
        else {
            $this->_addError('Произошла неизвестная ошибка, пожалуйста, попробуйте позднее');
        }

        return $this->response();
    }

    public function actionAddOrderPurchaseField()
    {
        $order_id = Yii::$app->request->post('order_id');

        if($order = Order::findOne($order_id)) {
            $html = $order->getPurchaseFieldHtml();
            if($html) {
                return $this->response($html);
            }
        }

        $this->_addError('Не удалось найти заказ');

        return $this->response();
    }

    public function actionAddPaPurchaseField()
    {
        $purchase_id = Yii::$app->request->post('purchase_id');

        if($purchase = Purchase::findOne($purchase_id)) {
            $html = $purchase->getPurchaseFieldHtml();
            if($html) {
                return $this->response($html);
            }
        }

        $this->_addError('Не удалось найти закупку');

        return $this->response();
    }

    public function actionDeleteOrderPurchase()
    {
        $purchase_id = Yii::$app->request->post('purchase_id');

        if($purchase = OrderPurchase::findOne($purchase_id)) {
            if($purchase->delete()) {
                return $this->response();
            }
            $this->_addError('Ошибка удаления поля');
            return $this->response();
        }

        $this->_addError('Не удалось найти заказ');

        return $this->response();
    }

    public function actionDeletePaPurchase()
    {
        $pa_id = Yii::$app->request->post('pa_id');

        if($purchase = PurchaseProductAttribute::findOne($pa_id)) {
            if($purchase->delete()) {
                return $this->response();
            }
            $this->_addError('Ошибка удаления поля');
            return $this->response();
        }

        $this->_addError('Не удалось найти заказ');

        return $this->response();
    }

    public function actionOrderPurchasesList()
    {
        $order_id = Yii::$app->request->post('order_id');

        if($order = Order::findOne($order_id)) {
            if($html = $order->getPurchasesTableList()) {
                return $this->response($html);
            }
            $this->_addError('Ошибка обновления товаров');
            return $this->response();
        }

        $this->_addError('Не удалось найти заказ');

        return $this->response();
    }

    public function actionPaPurchasesList()
    {
        $purchase_id = Yii::$app->request->post('purchase_id');

        if($purchase = Purchase::findOne($purchase_id)) {
            if($html = $purchase->getPaTableList()) {
                return $this->response($html);
            }
            $this->_addError('Ошибка обновления');
            return $this->response();
        }

        $this->_addError('Не удалось найти закупку');

        return $this->response();
    }

    public function actionUpdateOrderHeader()
    {
        $order_id = Yii::$app->request->post('order_id');

        if($order = Order::findOne($order_id)) {
            if($html = $order->getOrderHeaderHtml()) {
                return $this->response($html);
            }
            $this->_addError('Ошибка обновления');
            return $this->response();
        }

        $this->_addError('Не удалось найти заказ');

        return $this->response();
    }

    public function actionUpdatePaHeader()
    {
        $purchase_id = Yii::$app->request->post('purchase_id');

        if($purchase = Purchase::findOne($purchase_id)) {
            if($html = $purchase->getPurchaseHeaderHtml()) {
                return $this->response($html);
            }
            $this->_addError('Ошибка обновления');
            return $this->response();
        }

        $this->_addError('Не удалось найти заказ');

        return $this->response();
    }

    public function actionChangeOrderField()
    {
        $order_id = Yii::$app->request->post('order_id');
        $purchase_id = Yii::$app->request->post('purchase_id');
        $product_id = Yii::$app->request->post('product_id');
        $sizeName = Yii::$app->request->post('size');
        $qty = Yii::$app->request->post('qty');

        if(!$model = OrderPurchase::findOne($purchase_id)) {
            $model = new OrderPurchase();
            $model->order_id = $order_id;
        }
        $model->product_id = $product_id;
        if($size = ProductSize::findOne(['name' => $sizeName])) {
            $model->size_id = $size->id;
        }
        $model->qty = $qty;
        if($model->save()) {
            return $this->response('Заказ успешно обновлен');
        }

        $this->_addError('Не удалось обновить заказ');

        return $this->response();
    }

    public function actionChangePaField()
    {
        $purchase_id = Yii::$app->request->post('purchase_id');
        $pa_id = Yii::$app->request->post('pa_id');
        $product_attribute_id = Yii::$app->request->post('product_attribute_id');
        $qty = Yii::$app->request->post('qty');

        if(!$model = PurchaseProductAttribute::findOne($pa_id)) {
            $model = new PurchaseProductAttribute();
            $model->purchase_id = $purchase_id;
        }
        $model->product_attribute_id = $product_attribute_id;
        $model->qty = $qty;
        if($model->save()) {
            return $this->response('Закупка успешно обновлена');
        }

        $this->_addError('Не удалось обновить закупку');

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
