<?php

namespace humhub\modules\performance_insights;

use Yii;
use yii\base\BaseObject;
use yii\helpers\Url;

class Events extends \yii\base\BaseObject 
{ 
	public static function onAdminMenuInit($event) 
	{
		$event->sender->addItem([
			'label' => '<i class="fa fa-line-chart" aria-hidden="true"></i>'.Yii::t('PerformanceInsightsModule.base', 'Performance Insights'),
			'url' => Url::toRoute(['/performance_insights/admin/test']),
			'sortOrder' => 650,
			'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'performance_insights' && Yii::$app->controller->id == 'admin'),
			'isVisible' => Yii::$app->user->can(new \humhub\modules\admin\permissions\ManageModules()),
		]);
	}
}
