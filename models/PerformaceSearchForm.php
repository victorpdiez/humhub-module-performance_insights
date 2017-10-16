<?php

namespace humhub\modules\performance_insights\models;

use Yii;
use yii\base\Model;


class PerformaceSearchForm extends Model
{

	public $keyword;
	public $searchUrl;
	
	public function rules()
	{
		return [
			[['keyword','searchUrl'], 'required'],
			[['keyword'], 'string']
		];
	}
	
}