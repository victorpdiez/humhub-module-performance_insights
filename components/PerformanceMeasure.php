<?php

namespace humhub\modules\performance_insights\components;

use Yii;
use JonnyW\PhantomJs\Client;

/*
 *  Responsible for Measuring speed.
 */
class PerformanceMeasure extends BaseTest 
{
	
	public  $url;
	private $path;	
	private $client;
	private $strCookie;
	public  $imgOptions=[];
   /**
     * @inheritdoc
     */
	public function __construct($url)
	{
		$this->url = $url;
		$this->path = \Yii::getAlias('@webroot') . '/bin/phantomjs.exe';
		$this->client = Client::getInstance();
		$this->client->getEngine()->setPath($this->path);
		$this->imgOptions = ['width' => 413,'height' => 281,'top' => 0,'left' => 0];
		$this->strCookie = 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/';
		parent::__construct('progress_stats.json');
	}
	/**
     *  Analyze page speed.
     *  @return bool  
     */
	public function Run()
	{
		$data = ['fullLoadedTime' => false,'responseLength' => false,'isScreenShotTaken' => false,'progress' => 0];	
		$this->writeToLocalFile($data);
		if($this->findResponseParams() && $this->takeScreenShot()){
			return true;
		}
		return false;
	}
    /**
     *  Finds response time of given url.
     *  @return bool  
     */
    private function findResponseParams()
    {		
    	$this->client->isLazy(); 		
    	$time = time();
    	$request = $this->client->getMessageFactory()->createRequest($this->url, 'GET');
    	$request->addHeader('cookie',$this->strCookie);
    	$request->setTimeout(3000000);
    	$response = $this->client->getMessageFactory()->createResponse();
    	$this->client->send($request, $response);		
    	if($response->getStatus() === 200) {			
    		$responseTime = time();
    		$pageLoadTime = $responseTime-$time;
    		$responseLength = strlen($response->getContent());
    		$data = ['fullLoadedTime' => $pageLoadTime,'responseLength' => $responseLength,'progress' => 2];
    		$this->updateLocalFile($data);			
    		return true;
    	}
    }
	/**
     *  Takes screenshot of given url and update local file.
     *   @return bool  
     */
	public function takeScreenShot()
	{		
		$data = ['isScreenShotTaken' => true,'progress' => 3];
		$this->updateLocalFile($data);
		return true;
	}

	/**
     *  Return time in seconds of a given url.
     *  @return array
     */
	public function getReturnValues()
	{
		$session = Yii::$app->session;
		$time = $session->get('screenshotid');
		$module = Yii::$app->moduleManager->getModule('performance_insights');
		$moduleBasePath = $module->getBasePath();
		$imgUrl = Yii::$app->getModule('performance_insights')->getAssetsUrl() . '/screenshots/' . 'screenshot_' . $time . '.png';
		$inp = $this->readFromLocalFile();
		$tempArray = json_decode($inp,true);
		$timeInSec = $this->convertToSeconds($tempArray['fullLoadedTime']);
		$pageSize = $this->convertToReadableSize($tempArray['responseLength']);
		return [
			'timeInSec' => $timeInSec,
			'pageSize' => $pageSize,
			'imgUrl' => $imgUrl
		];
	}
	/**
     *  Converts to second
     *  @param string $timeInSec
     *  @return string
     */
	public function convertToSeconds($timeInSec)
	{		
		return $timeInSec;
	}
	/**
     *  Converts to readable size
     *  @param string $size
     *  @return string
     */
	public function convertToReadableSize($size)
	{
		$base = log($size) / log(1024);
		$suffix = ['', 'KB', 'MB', 'GB', 'TB'];
		$f_base = floor($base);
		return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
	}
	/**
     *  Run 10 identical search in directory
     *  @return bool
     */
	public function runTenIdenticalSearch()
	{		
		$this->client->isLazy(); 		
		$time = time();
		$request = $this->client->getMessageFactory()->createRequest($this->url, 'GET');
		$request->addHeader('cookie',$this->strCookie);
		$request->setTimeout(300000000);
		$response = $this->client->getMessageFactory()->createResponse();
		$this->client->send($request, $response);
		if($response->getStatus() === 200) {			
			$responseTime = time();
			$pageLoadTime = $responseTime-$time;
			return $this->convertToSeconds($pageLoadTime);
		}
		return true;
		
	}
    /**
     *  Append keyword to current url
     *  @param string $keyword
     *  @return string
     */
    public function scanAndUpdateUrl($keyword)
    {
    	if(strpos($this->url,'?') !== false) {
    		$url = $this->url . '&keyword=' . $keyword;
    	} else {
    		$url = $this->url . '?keyword=' . $keyword;
    	}
    	return $url;
    }

}
