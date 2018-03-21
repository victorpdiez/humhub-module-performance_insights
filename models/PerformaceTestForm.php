<?php

namespace humhub\modules\performance_insights\models;

use Yii;
use yii\base\Model;

/**
 *  Validates page form
 */
class PerformaceTestForm extends Model
{
    public $url;

    public function rules()
    {
        return [
            [['url'], 'required'],
            /*[['url'], 'url']*/
        ];
    }
}
