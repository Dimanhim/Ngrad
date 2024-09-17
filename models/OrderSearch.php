<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form of `app\models\Order`.
 */
class OrderSearch extends Order
{
    public $_created_from;
    public $_created_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'date_order', 'client_id', 'phone', 'price', '_created_from', '_created_to'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::findSearch();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->_created_from and $this->_created_to) {
            $query->andWhere(['between', 'date_order', strtotime($this->_created_from), strtotime($this->_created_to) + (60 * 60 * 24) - 1]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_order' => $this->date_order,
            'date_shipping' => $this->date_shipping,
            'client_id' => $this->client_id,
            'price' => $this->price,
            'status_id' => $this->status_id,
            'is_active' => $this->is_active,
            'deleted' => $this->deleted,
            'position' => $this->position,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
