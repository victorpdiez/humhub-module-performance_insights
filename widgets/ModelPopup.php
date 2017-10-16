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


class ModelPopup extends Widget 
{
	public function run()
	{ 
		return $this->render('model-popup'); 

	}


}
