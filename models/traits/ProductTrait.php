<?php

namespace app\models\traits;

use app\components\Helpers;
use app\models\ProductAttributeCategory;
use app\models\ProductAttributesRelation;
use app\models\ProductCollection;

trait ProductTrait
{

    /**
     * @throws \yii\db\Exception
     */
    public function handleAttributes()
    {
        if(
            $this->_cat_fields and
            isset($this->_cat_fields['attributes']) and
            isset($this->_cat_fields['values']) and
            $this->_cat_fields['attributes'] and
            $this->_cat_fields['values']
        )
        {
            ProductAttributesRelation::deleteAll(['product_id' => $this->id]);

            foreach($this->_cat_fields['attributes'] as $key => $attributeId) {
                if($attributeId and isset($this->_cat_fields['values'][$key]) and $this->_cat_fields['values'][$key]) {
                    $model = new ProductAttributesRelation();
                    $model->product_id = $this->id;
                    $model->product_attribute_id = $attributeId;
                    $model->qty = Helpers::prepareFloat($this->_cat_fields['values'][$key]);
                    $model->save();
                }
            }
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    public function handleCollection()
    {
        if($this->_free_field) {
            $model = new ProductCollection();
            $model->name = $this->_free_field;
            if($model->save()) {
                $this->collection_id = $model->id;
            }
        }
    }

    /**
     *
     */
    public function setRelations()
    {
        $maxAttributeCount = 0;
        if($this->_categories) return false;

        $data = $this->setDefaultRelations();

        $productRelations = $this->productRelations;

        if($productRelations) {
            foreach($productRelations as $productRelation) {
                if($productAttribute = $productRelation->productAttribute) {
                    if($productAttributeCategory = $productAttribute->category) {
                        $data[$productAttributeCategory->id]['product_attributes'][$productAttribute->id] = [
                            'product_attribute' => $productAttribute,
                            'product_relation' => $productRelation,
                        ];
                        $maxAttributeCount += 1;
                    }
                }
            }
        }

        $this->_categories = $data;
        $this->maxAttributeCount = $maxAttributeCount;

        return $data;

        /*if($productRelations = $this->productRelations) {
            foreach($productRelations as $productRelation) {
                $this->_categories['product_attribute_id'][$productRelation->id] = $productRelation->product_attribute_id;
                $this->_categories['values'][$productRelation->id] = $productRelation->qty;
            }
        }*/
    }

    public function setDefaultRelations()
    {
        $data = [];

        if($productAttributeCategories = ProductAttributeCategory::findModels()->all()) {
            foreach($productAttributeCategories as $productAttributeCategory) {
                $data[$productAttributeCategory->id] = [
                    'product_attribute_category' => $productAttributeCategory,
                    'product_attributes' => [],
                ];

                /*if($productAttributes = $productAttributeCategory->productAttributes) {
                    foreach($productAttributes as $productAttribute) {
                        $data[$productAttributeCategory->id]['product_attributes'][] = [
                            'product_attribute' => null,
                            'relation' => null,
                        ];
                    }
                }*/
            }
        }

        return $data;
    }

    public function setCostValues()
    {

    }
}
