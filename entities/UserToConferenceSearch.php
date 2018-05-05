<?php

namespace app\entities;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserToConferenceSearch represents the model behind the search form of `app\entities\UserToConference`.
 */
class UserToConferenceSearch extends UserToConference
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'conference_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = UserToConference::find()->with('conference');

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

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'conference_id' => $this->conference_id,
        ]);

        return $dataProvider;
    }
}
