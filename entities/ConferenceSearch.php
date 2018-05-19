<?php

namespace app\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\entities\Conference;

/**
 * ConferenceSearch represents the model behind the search form of `app\entities\Conference`.
 */
class ConferenceSearch extends Conference
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'author_id', 'start_time', 'end_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description'], 'safe'],
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
        $query = Conference::find()
            ->where(['deleted' => 0]);

        if (!empty($params['time'])) {
            if ($params['time'] == 'now') {
                $query->where(['<', 'start_time', time()])->andWhere(['is', 'end_time', null]);
            } elseif ($params['time'] == 'future') {
                $query->where(['>', 'start_time', time()]);
            } elseif ($params['time'] == 'history') {
                $query->where(['<', 'start_time', time()])->andWhere(['is not', 'end_time', null]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'start_time' => SORT_ASC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'author_id' => $this->author_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
