<?php

namespace humhub\modules\performance_insights\widgets;

use Yii;
use yii\helpers\Url;

/**
 *  Render TabMenu in Admin section
 */
class TabMenu extends \humhub\widgets\BaseMenu
{

  public $template = "@humhub/widgets/views/tabMenu";
  public $type = "adminUserSubNavigation";

  public function init()
  {
   $this->addItem([
    'label' => Yii::t('PerformanceInsightsModule.base', 'Analyze'),
    'url' => Url::to(['/performance_insights/admin/test']),
    'sortOrder' => 100,
    'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'performance_insights' && Yii::$app->controller->id == 'admin'&& Yii::$app->controller->action->id == 'test'),
  ]);
   $this->addItem([
    'label' =>Yii::t('PerformanceInsightsModule.base', 'Settings'),
    'url' => Url::to(['/performance_insights/admin/index']),
    'sortOrder' => 101,
    'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'performance_insights' && Yii::$app->controller->id == 'admin' && Yii::$app->controller->action->id == 'index'),
  ]);
   $this->addItem([
    'label' =>Yii::t('PerformanceInsightsModule.base', 'Test History'),
    'url' => Url::to(['/performance_insights/admin/test-history']),
    'sortOrder' => 102,
    'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'performance_insights' && Yii::$app->controller->id == 'admin' && Yii::$app->controller->action->id == 'test-history'),
  ]);

   parent::init();
 }
}
