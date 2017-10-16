<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\performance_insights\widgets;

use Yii;
use yii\helpers\Url;
use humhub\components\Widget;


class LoadingMenu extends Widget 
{
	public $id;

	public function run()
	{ 
		return $this->render('loader-menu',['id'=>$this->id]); 

	}

}
