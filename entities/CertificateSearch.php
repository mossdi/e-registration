<?php

namespace app\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\entities\Certificate;

/**
 * CertificateSearch represents the model behind the search form of `app\entities\Certificate`.
 */
class CertificateSearch extends Certificate
{
    public $userLastName;
    public $userFirstName;
    public $userPatronName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'conference_id', 'date_issue', 'status', 'deleted', 'created_at', 'updated_at'], 'integer'],
            [['userLastName', 'userFirstName', 'userPatronName', 'document_series', 'verification_code'], 'safe'],
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
        $query = Certificate::find()
            ->joinWith(['user', 'conference'])
               ->where(['certificate.deleted' => 0]);

        if (!empty($params['form'])) {
            if ($params['form'] == 'ready') {
                $query->where(['is not', 'certificate.document_series', null]);
            } elseif ($params['form'] == 'empty') {
                $query->where(['is', 'certificate.document_series', null]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'user.last_name',
                    'user.first_name',
                    'user.patron_name',
                    'conference.title',
                    'conference.start_time',
                    'date_issue',
                    'document_series',
                    'verification_code',
                ],
                'defaultOrder' => [
                    'conference.start_time' => SORT_ASC,
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
            'certificate.date_issue' => $this->date_issue,
            'certificate.conference_id' => $this->conference_id,
        ]);

        $query->andFilterWhere(['like', 'user.last_name', $this->userLastName])
              ->andFilterWhere(['like', 'user.first_name', $this->userFirstName])
              ->andFilterWhere(['like', 'user.patron_name', $this->userPatronName])
              ->andFilterWhere(['like', 'certificate.document_series', $this->document_series])
              ->andFilterWhere(['like', 'certificate.verification_code', $this->verification_code]);

        return $dataProvider;
    }
}
