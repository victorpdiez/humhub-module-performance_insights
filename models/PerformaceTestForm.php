<?php

namespace humhub\modules\performance_insights\models;

use Yii;
use yii\base\Model;


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
