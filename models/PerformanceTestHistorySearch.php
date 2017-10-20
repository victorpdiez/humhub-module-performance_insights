<?php

namespace humhub\modules\performance_insights\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use humhub\modules\performance_insights\models\PerformanceTestHistory;

/**
 * PerformanceTestHistorySearch represents the model behind the search form about `humhub\modules\performance_insights\models\PerformanceTestHistory`.
 */
class PerformanceTestHistorySearch extends PerformanceTestHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'page_load_time'], 'integer'],
            [['url','report_time'], 'safe'],
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
        $query = PerformanceTestHistory::find();

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
            'id' => $this->id,
            'page_load_time' => $this->page_load_time
        ]);
        $query->andFilterWhere(['>=', 'report_time', strtotime($this->report_time .'00:00:00')]);
        $query->andFilterWhere(['<=', 'report_time', strtotime($this->report_time .'23:59:59')]);
        $query->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
