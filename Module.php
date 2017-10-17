<?php
namespace humhub\modules\performance_insights;

use Yii;
use humhub\models\Setting;

class Module extends \humhub\components\Module
{
	public $controllerNamespace = 'humhub\modules\performance_insights\controllers';


    public function disable()
    {
    	parent::disable();
    	
    }

}
