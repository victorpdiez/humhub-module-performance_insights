<?php
namespace humhub\modules\performance_insights\widgets;

use Yii;
use yii\helpers\Url;
use humhub\components\Widget;

/*
 *  Render Loading Popup
 */
class ModelPopup extends Widget 
{
	public function run()
	{ 
		return $this->render('model-popup'); 

	}


}
