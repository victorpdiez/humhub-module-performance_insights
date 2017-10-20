<?php

namespace humhub\modules\performance_insights\models;

use Yii;

/**
 * This is the model class for table "performance_test_history".
 *
 * @property integer $id
 * @property string $url
 * @property integer $page_load_time
 * @property integer $report_time
 */
class PerformanceTestHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'performance_test_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'string'],
            [['page_load_time', 'report_time'], 'required'],
            [['page_load_time', 'report_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'page_load_time' => 'Page Load Time',
            'report_time' => 'Report Time',
        ];
    }
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->report_time=time();
            return true;
        } else {
            return false;
        }
    }
}
