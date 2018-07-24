<?php

namespace app\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\entities\ConferenceParticipant;

/**
 * ConferenceParticipantSearch represents the model behind the search form of `app\entities\ConferenceParticipant`.
 */
class ConferenceParticipantSearch extends ConferenceParticipant
{
    public $userLastName;
    public $userFirstName;
    public $userPatronName;
    public $userOrganization;
    public $userPost;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'conference_id', 'reseption_id', 'created_at', 'updated_at'], 'integer'],
            [['userLastName', 'userFirstName', 'userPatronName', 'userOrganization', 'userPost', 'method'], 'safe'],
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
     * @param $conference_id
     * @return ActiveDataProvider
     */
    public function search($params, $conference_id)
    {
        $query = ConferenceParticipant::find()
            ->joinWith(['user'])
               ->where(['conference_id' => $conference_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 50
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
            'user_id' => $this->user_id,
            'conference_id' => $this->conference_id,
            'reseption_id' => $this->reseption_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'method', $this->method])
              ->andFilterWhere(['like', 'user.last_name', $this->userLastName])
              ->andFilterWhere(['like', 'user.first_name', $this->userFirstName])
              ->andFilterWhere(['like', 'user.patron_name', $this->userPatronName])
              ->andFilterWhere(['like', 'user.organization', $this->userOrganization])
              ->andFilterWhere(['like', 'user.post', $this->userPost]);

        return $dataProvider;
    }
}
