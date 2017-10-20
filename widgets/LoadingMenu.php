<?php
namespace humhub\modules\performance_insights\widgets;

use Yii;
use yii\helpers\Url;
use humhub\components\Widget;

/**
 *  Render Loading Icon
 */
class LoadingMenu extends Widget 
{
	public $id;

	public function run()
	{ 
		return $this->render('loader-menu',['id'=>$this->id]); 

	}

}
