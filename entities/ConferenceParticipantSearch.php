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
    public $userEmail;
    public $certificateVerificationCode;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'conference_id', 'reception_id', 'created_at', 'updated_at'], 'integer'],
            [['userLastName', 'userFirstName', 'userPatronName', 'userOrganization', 'userPost', 'userEmail', 'method', 'certificateVerificationCode'], 'safe'],
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
     * @param null $conference_id
     * @return ActiveDataProvider
     */
    public function search($params, $conference_id = null)
    {
        $query = ConferenceParticipant::find()
            ->joinWith(['conference'])
            ->joinWith(['certificate'])
            ->joinWith(['user']);

        if ($conference_id != null) {
            $query->where(['conference_participant.conference_id' => $conference_id]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'userLastName' => [
                        'asc'  => ['user.last_name' => SORT_ASC],
                        'desc' => ['user.last_name' => SORT_DESC],
                    ],
                    'userFirstName' => [
                        'asc'  => ['user.first_name' => SORT_ASC],
                        'desc' => ['user.first_name' => SORT_DESC],
                    ],
                    'userPatronName' => [
                        'asc'  => ['user.patron_name' => SORT_ASC],
                        'desc' => ['user.patron_name' => SORT_DESC],
                    ],
                    'userOrganization' => [
                        'asc'  => ['user.organization' => SORT_ASC],
                        'desc' => ['user.organization' => SORT_DESC],
                    ],
                    'userPost' => [
                        'asc'  => ['user.post' => SORT_ASC],
                        'desc' => ['user.post' => SORT_DESC],
                    ],
                    'userEmail' => [
                        'asc'  => ['user.email' => SORT_ASC],
                        'desc' => ['user.email' => SORT_DESC],
                    ],
                    'certificateVerificationCode' => [
                        'asc'  => ['certificate.verification_code' => SORT_ASC],
                        'desc' => ['certificate.verification_code' => SORT_DESC],
                    ],
                    'conference.title',
                    'conference.start_time',
                    'created_at',
                ],
                'defaultOrder' => [
                    'conference.start_time' => SORT_DESC,
                ],
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
            'reception_id' => $this->reception_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'method', $this->method])
              ->andFilterWhere(['like', 'user.last_name', $this->userLastName])
              ->andFilterWhere(['like', 'user.first_name', $this->userFirstName])
              ->andFilterWhere(['like', 'user.patron_name', $this->userPatronName])
              ->andFilterWhere(['like', 'user.organization', $this->userOrganization])
              ->andFilterWhere(['like', 'user.post', $this->userPost])
              ->andFilterWhere(['like', 'user.email', $this->userEmail])
              ->andFilterWhere(['like', 'certificate.verification_code', $this->certificateVerificationCode]);

        return $dataProvider;
    }
}
