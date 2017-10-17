<?php
namespace humhub\modules\performance_insights;

use Yii;
use yii\base\Object;
use yii\helpers\Url;

class Events extends \yii\base\Object 
{ 

	/*
		Add performace insights menu item to Administration menu

	*/
		public static function onAdminMenuInit($event) 
		{
			$event->sender->addItem(array(
				'label' => '<i class="fa fa-line-chart" aria-hidden="true"></i>'.Yii::t('PerformanceInsightsModule.base', 'Performace Insights'),
				'url' => Url::toRoute(['/performance_insights/admin/test']),
				'sortOrder' => 650,
				'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'performance_insights' && Yii::$app->controller->id == 'admin'),
			));
		}
}
