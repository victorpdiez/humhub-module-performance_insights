<?php

namespace humhub\modules\performance_insights;

use yii\web\AssetBundle;
use yii;
use yii\web\View;

class Assets extends AssetBundle 
{
    public $sourcePath;

    public $js = [        
    'js/main.js'
    ];

    public $css = [
    'css/style.css'
    ];

    public function init() {
        $baseurl = Yii::$app->request->baseUrl;
        $this->sourcePath = 'protected/modules/performance_insights/assets';
        $this->jsOptions['position']  = View::POS_BEGIN; 
        $this->cssOptions['position'] =View::POS_BEGIN; 
        parent::init();
    }
    public $depends = ['humhub\\assets\\AppAsset'];
}
