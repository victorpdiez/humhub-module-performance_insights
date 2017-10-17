<?php
namespace humhub\modules\performance_insights\components;
use Yii;
/*
 *  Manage json file
 */
class BaseTest
{

	private $jsonFilePath;

	public function __construct($file=null)
	{
		$this->jsonFile='test_history.json';	
		if($file)
		{
			$this->jsonFile=$file;			
		}				
	}

  /*  
   *  returns json file path
   *  @return string 
   */
  protected function getJsonFilePath()
  {		
  	$module= Yii::$app->moduleManager->getModule('performance_insights');
  	$moduleBasePath = $module->getBasePath();	
  	$this->jsonFilePath=$moduleBasePath . DIRECTORY_SEPARATOR .'test_history'.DIRECTORY_SEPARATOR.$this->jsonFile;
  	return $this->jsonFilePath;
  }

  /*  
   *  write to json file
   *  @return bool  
   */
  protected function writeToLocalFile($data)
  {		
  	if(file_exists($this->getJsonFilePath()))
  	{
  		$inp = $this->readFromLocalFile();
  		$tempArray = json_decode($inp,true);	
  		$tempArray=array_merge($tempArray, $data);
  	}else{
  		$tempArray=$data;
  	}		
  	$jsonData = json_encode($tempArray);
  	file_put_contents($this->getJsonFilePath(),$jsonData);
  	return true;
  }

  /*  
   *  update json file
   *  @return bool  
   */
  protected function updateLocalFile($data)
  {	
  	$inp = $this->readFromLocalFile();
  	$tempArray = json_decode($inp,true);
  	foreach($data as $key=>$value)
  	{
  		$tempArray[$key]=$value;
  	}
  	$jsonData = json_encode($tempArray);
  	file_put_contents($this->getJsonFilePath(),$jsonData);
  	return true;
  }

  /*  
   *  read json file
   */
  public function readFromLocalFile()
  {
  	return file_get_contents($this->getJsonFilePath());
  }

  /*  
   *  delete selected items
   *  @return bool  
   */
  protected function deleteSelectedLocalItems($type='space')
  {
  	$inp = $this->readFromLocalFile();
  	$tempArray = json_decode($inp,true);
  	$modArray=array();
  	foreach($tempArray as $key=>$value)
  	{
  		if($value['type']!=$type)
  		{
  			$modArray[]=$tempArray[$key];
  		}
  	}
  	$jsonData = json_encode($modArray);
  	file_put_contents($this->getJsonFilePath(),$jsonData);
  	return true;
  }

  /*  
   *  check selected item exist or not
   *  @return bool  
   */
  public function isTestItemExist($type='space')
  {
  	$inp = $this->readFromLocalFile();
  	$tempArray = json_decode($inp,true);
  	$found=0;
  	foreach($tempArray as $key=>$value){
  		if($value['type']==$type)
  		{
  			$found++;
  		}
  	}
  	return $found>0?true:false;
  }
}