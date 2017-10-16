<?php

namespace humhub\modules\performance_insights\controllers;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use humhub\modules\admin\components\Controller;
use humhub\modules\performance_insights\models\PerformaceTestForm;
use humhub\modules\performance_insights\models\PerformaceSearchForm;
use humhub\modules\performance_insights\components\PerformanceMeasure;
use humhub\modules\performance_insights\components\BaseTest;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

class AdminController extends Controller 
{

    public function init() 
    {
        $this->subLayout = '/layouts/tab_layout';
        return parent::init();
    }

    public function actionIndex() 
    {
        $generateSpace = new \humhub\modules\performance_insights\components\GenerateSpace();
        $generateUser = new \humhub\modules\performance_insights\components\GenerateUser();
        $isDeleteSpaceButtonHidden = $generateSpace->isTestItemExist('space');
        $isDeleteUserButtonHidden = $generateUser->isTestItemExist('user');

        if (Yii::$app->request->isAjax && Yii::$app->request->post()) 
        {

            echo $this->renderAjax('index_content', [
                'isDeleteSpaceButtonHidden' => $isDeleteSpaceButtonHidden,
                'isDeleteUserButtonHidden' => $isDeleteUserButtonHidden
            ]);
            \Yii::$app->end();
        }
        return
                $this->render('index', [
                    'isDeleteSpaceButtonHidden' => $isDeleteSpaceButtonHidden,
                    'isDeleteUserButtonHidden' => $isDeleteUserButtonHidden
        ]);
    }

    public function actionTest() 
    {
        $performaceTestForm = new PerformaceTestForm();
        $performaceSearchForm = new PerformaceSearchForm();
        $urls = $this->getSearchUrl($keyword = false);
        if (Yii::$app->request->isAjax && $performaceTestForm->load(Yii::$app->request->post())) 
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $validationError = ActiveForm::validate($performaceTestForm);
            if (count($validationError)) 
            {
                return ActiveForm::validate($performaceTestForm);
                \Yii::$app->end();
            }
            $url = Yii::$app->request->post('PerformaceTestForm')['url'];
            $performanceMeasure = new PerformanceMeasure($url);
            if ($performanceMeasure->run()) 
            {
                $returnValues = $performanceMeasure->getReturnValues();
                return
                        [
                            'timeInSec' => $returnValues['timeInSec'],
                            'pageSize' => $returnValues['pageSize'],
                            'imgUrl' => $returnValues['imgUrl']
                ];
            }
            \Yii::$app->end();
        }
        return $this->render('test', ['performaceTestForm' => $performaceTestForm, 'performaceSearchForm' => $performaceSearchForm, 'urls' => array_keys($urls)]);
    }

    public function actionTestSearch()
    {
        $performaceSearchForm = new PerformaceSearchForm();
        if (Yii::$app->request->isAjax && $performaceSearchForm->load(Yii::$app->request->post())) 
        {

            Yii::$app->response->format = Response::FORMAT_JSON;
            $validationError = ActiveForm::validate($performaceSearchForm);
            if (count($validationError)) 
            {
                return ActiveForm::validate($performaceSearchForm);
                \Yii::$app->end();
            }
            $keyword = $performaceSearchForm->keyword;
            $urls = array_values($this->getSearchUrl($keyword));
            $keys = array_keys($this->getSearchUrl($keyword));
            $url = $urls[$performaceSearchForm->searchUrl];
            $response['url'] = $url;
            $response = array();
            $totalTimeTaken = 0;
            $avgTimeTaken = 0;
            for ($i = 0; $i < 10; $i++) 
            {
                $performanceMeasure = new PerformanceMeasure($url);
                $timeTaken = $performanceMeasure->runTenIdenticalSearch($keyword);
                $totalTimeTaken+=$timeTaken;
            }
            $avgTimeTaken = $totalTimeTaken / 10;
            $response['output'] = $avgTimeTaken;
            $response['success'] = true;

            return $response;
            \Yii::$app->end();
        }
    }

    public function actionGenerate() 
    {
        if (Yii::$app->request->post()) {
            $className = $this->getClassName();
            $test = new $className(Yii::$app->request->post('number'));
            $test->generateData();
            $type = ucfirst(Yii::$app->request->post('type'));
            Yii::$app->session->setFlash('success', Yii::t('PerformanceInsightsModule.base', "Success! {X} Test {content} generated successfully.", ['{content}' => $type . 's', '{X}' => Yii::$app->request->post('number')]));
        }
    }

    public function actionDelete() 
    {
        if (Yii::$app->request->post()) {
            $className = $this->getClassName();
            $test = new $className(Yii::$app->request->post('number'));
            $test->deleteData();
            $type = ucfirst(Yii::$app->request->post('type'));
            Yii::$app->session->setFlash('success', Yii::t('PerformanceInsightsModule.base', "Success! Test {content} deleted successfully.", ['{content}' => $type . 's']));
        }
    }

    public function actionCurrentProgress() 
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $baseTest = new BaseTest('progress_stats.json');
        $inp = $baseTest->readFromLocalFile();
        $tempArray = json_decode($inp, true);
        return[
            'progress' => $tempArray['progress']
        ];
    }

    private function getClassName() 
    {
        $type = ucfirst(Yii::$app->request->post('type'));
        $componentNameSpace = '\humhub\modules\performance_insights\components';
        $className = 'Generate' . $type;
        return $componentNameSpace . "\\" . $className;
    }

    private function getSearchUrl($keyword = false) 
    {
        if (Yii::$app->hasModule('categories_and_types')) {
            $globalSearchUrl = Yii::$app->urlManager->createAbsoluteUrl(['categories_and_types/search/index', 'keyword' => $keyword]);

            $spaceSearchUrl = Yii::$app->urlManager->createAbsoluteUrl(['categories_and_types/directory/spaces', 'keyword' => $keyword]);
        } else {
            $globalSearchUrl = Yii::$app->urlManager->createAbsoluteUrl(['search/search/index', 'keyword' => $keyword]);
            $spaceSearchUrl = Yii::$app->urlManager->createAbsoluteUrl(['directory/directory/spaces', 'keyword' => $keyword]);
        }
        $membersSearchUrl = Yii::$app->urlManager->createAbsoluteUrl(['directory/directory/members', 'keyword' => $keyword]);
        return
                [
                    'space' => $spaceSearchUrl,
                    'members' => $membersSearchUrl
        ];
    }

}
