<?php

namespace app\controllers;

use app\models\ProductAttribute;
use app\models\ProductAttributeCategorySearch;
use app\models\ProductAttributeSearch;
use app\models\Purchase;
use app\models\Supplier;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\PurchaseForm;

/**
 * ProductAttributeController implements the CRUD actions for ProductAttribute model.
 */
class ProductAttributeController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'className' => ProductAttribute::className(),
            ]
        );
    }

    /**
     * Lists all ProductAttribute models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductAttributeCategorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductAttribute model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProductAttribute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($category_id = null)
    {
        $model = new ProductAttribute(['category_id' => $category_id]);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductAttribute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionCreatePurchase()
    {
        $model = new PurchaseForm();
        if($model->load(\Yii::$app->request->post())) {
            if($model->validate()) {
                $purchase = new Purchase();
                $purchase->_purchases = json_decode($model->data, true);
                $purchase->supplier_id = $model->supplier_id;
                $purchase->date_purchase = date('d.m.Y H:i:s');

                if($purchase->save()) {
                    return $this->redirect(['purchase/view', 'id' => $purchase->id]);
                }
            }
            return $this->redirect(['index']);
        }
    }
}
