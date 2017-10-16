<?php

namespace humhub\modules\performance_insights\components;
use Yii;
use JonnyW\PhantomJs\Client;


class PerformanceMeasure extends BaseTest 
{
	
	public  $url;
	private $path;	
	private $client;
	private $strCookie;
	public  $imgOptions=[];

	public function __construct($url)
	{
		$this->url=$url;
		$this->path=\Yii::getAlias('@webroot').'/bin/phantomjs.exe';
		$this->client=Client::getInstance();
		$this->client->getEngine()->setPath($this->path);
		$this->imgOptions=['width'=>413,'height'=>281,'top'=>0,'left'=>0];
		$this->strCookie='PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/';
		parent::__construct('progress_stats.json');
	}

	public function Run()
	{
		$data=array('fullLoadedTime'=>false,'responseLength'=>false,'isScreenShotTaken'=>false,'progress'=>0);	
		$this->writeToLocalFile($data);
		if($this->findResponseParams() && $this->takeScreenShot()){
			return true;
		}
		return false;
	}

	private function findResponseParams()
	{		
		$this->client->isLazy(); 		
		$time = time();
		$request = $this->client->getMessageFactory()->createRequest($this->url, 'GET');
		$request->addHeader('cookie',$this->strCookie);
		$request->setTimeout(15000);
		$response = $this->client->getMessageFactory()->createResponse();
		$this->client->send($request, $response);	
		if($response->getStatus() === 200) {			
			$responseTime=time();
			$pageLoadTime=$responseTime-$time;
			$responseLength=strlen($response->getContent());
			$data=array('fullLoadedTime'=>$pageLoadTime,'responseLength'=>$responseLength,'progress'=>2);
			$this->updateLocalFile($data);			
			return true;
		}
	}

	public function takeScreenShot()
	{
		$session = Yii::$app->session;	
		$request = $this->client->getMessageFactory()->createCaptureRequest($this->url, 'GET');    
		$request->addHeader('cookie',$this->strCookie);
		$request->setTimeout(10000);
		$time=time();
		$session->set('screenshotid', $time);
		$request->setOutputFile(\Yii::getAlias('@webroot').'/protected/modules/performance_insights/assets/screenshots/screenshot_'.$time.'.png');
		$request->setViewportSize($this->imgOptions['width'], $this->imgOptions['height']);
		$request->setCaptureDimensions($this->imgOptions['width'], $this->imgOptions['height'], $this->imgOptions['top'], $this->imgOptions['left']);
		$response = $this->client->getMessageFactory()->createResponse();
		$this->client->send($request, $response);
		$data=array('isScreenShotTaken'=>true,'progress'=>3);
		$this->updateLocalFile($data);
		return true;
	}


	public function getReturnValues()
	{
		$session = Yii::$app->session;
		$time=$session->get('screenshotid');
		$module= Yii::$app->moduleManager->getModule('performance_insights');
		$moduleBasePath = $module->getBasePath();
		$imgUrl=Yii::$app->getModule('performance_insights')->getAssetsUrl().'/screenshots/'.'screenshot_'.$time.'.png';
		$inp =$this->readFromLocalFile();
		$tempArray = json_decode($inp,true);
		$timeInSec=$this->convertToSeconds($tempArray['fullLoadedTime']);
		$pageSize=$this->convertToReadableSize($tempArray['responseLength']);
		return [
		'timeInSec'=>$timeInSec,
		'pageSize'=>$pageSize,
		'imgUrl'=>$imgUrl
		];
	}

	public function convertToSeconds($timeInSec)
	{		
		return $timeInSec;
	}

	public function convertToReadableSize($size)
	{
		$base = log($size) / log(1024);
		$suffix = array("", "KB", "MB", "GB", "TB");
		$f_base = floor($base);
		return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
	}

	public function runTenIdenticalSearch()
	{		
		$this->client->isLazy(); 		
		$time = time();
		$request = $this->client->getMessageFactory()->createRequest($this->url, 'GET');
		$request->addHeader('cookie',$this->strCookie);
		$request->setTimeout(15000);
		$response = $this->client->getMessageFactory()->createResponse();
		$this->client->send($request, $response);			
		if($response->getStatus() === 200) {			
			$responseTime=time();
			$pageLoadTime=$responseTime-$time;
			return $this->convertToSeconds($pageLoadTime);
		}
		return true;
		
	}

	public function scanAndUpdateUrl($keyword)
	{
		if(strpos($this->url,'?') !== false) {
			$url=$this->url.'&keyword='.$keyword;
		} else {
			$url=$this->url.'?keyword='.$keyword;
		}
		return $url;
	}

}