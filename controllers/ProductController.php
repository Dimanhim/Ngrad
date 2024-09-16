<?php

namespace app\controllers;

use app\models\Client;
use app\models\Order;
use app\models\OrderForm;
use app\models\Product;
use app\models\ProductSearch;
use app\models\ProductSize;
use app\models\Supplier;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'className' => Product::className(),
            ]
        );
    }

    /**
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $products = Product::findModels()->all();

        $totalCount = $dataProvider->totalCount;

        /*$product = Product::findOne(7);
        $product->setRelations();*/

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

            'products' => $products,
            'productSizes' => ProductSize::getList(),
            'productCount' => Product::getPurchasesCount(),
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $model->setRelations();

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($collection_id = null)
    {
        $model = new Product(['collection_id' => $collection_id]);

        $model->setRelations();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->setRelations();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionCreateOrder()
    {
        $model = new OrderForm();
        if($model->load(\Yii::$app->request->post())) {
            if($model->validate()) {
                $order = new Order();
                $order->_purchases = json_decode($model->data, true);
                if($model->supplier_id) $order->client_id = $model->supplier_id;
                elseif($model->supplier_name) {
                    $client = new Client();
                    $client->name = $model->supplier_name;
                    if($client->save()) {
                        $order->client_id = $client->id;
                    }
                }
                $order->date_order = time();

                $order->setPrice();


                if($order->save()) {
                    return $this->redirect(['order/view', 'id' => $order->id]);
                }
            }
            return $this->redirect(['index']);
        }
    }
}
