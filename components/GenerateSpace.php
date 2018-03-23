<?php

namespace humhub\modules\performance_insights\components;

use Yii;
use humhub\modules\performance_insights\interfaces\TestInterface;
use humhub\modules\performance_insights\components\BaseTest;
use humhub\modules\space\models\Space;

/*
 *  Manage space Testing
 */

class GenerateSpace extends BaseTest implements TestInterface 
{

	public  $number;
	private $space_name_prefix = 'TEST_SPACE';
	private $faker;
	private $registration;
	private $userId;
	private $spaceCounter;

 /**
  * @inheritdoc
  */
	public function __construct($number=false)
	{
		$this->number = $number;
		$this->faker = \Faker\Factory::create();
		$this->spaceCounter = 0;
		parent::__construct('test_history.json');
	}
   /**
    *  Generate Faker Spaces.
    *  @return bool  
    */
    public function generateData()
    {
    	$data = [];
    	for($i = 0; $i < $this->number; $i ++)
    	{
    		$model = $this->createSpaceModel();
    		$model->name = $this->space_name_prefix.'_'.time() . ++ $this->spaceCounter;
    		$model->description = $this->faker->text($maxNbChars = 45);
    		$model->color = '#d1d1d1';
    		$model->save(false);
    		$data[] = ['id' => $model->id,'type' => 'space'];
    	}		
    	$this->writeToLocalFile($data);	
    	return true;			
    }
   /**
    *  Remove Faker Generated Spaces.
    */
   public function deleteData(){
   	$arrayId = $this->getAllSpaceId($this->readFromLocalFile());		
   	foreach($arrayId as $id)
   	{
   		$space=Space::find()->where(['id' => $id])->one();
   		if($space)
   		{
   			$space->delete();
   		}
   	}
   	$this->deleteSelectedLocalItems('space');
   }
   /**
    *  returns Space Model.
    *  @return string  
    */
   protected function createSpaceModel() 
   {
   	$model = new Space();
   	$model->scenario = 'create';
   	$model->visibility = Yii::$app->getModule('space')->settings->get('defaultVisibility');
   	$model->join_policy = Yii::$app->getModule('space')->settings->get('defaultJoinPolicy');
   	return $model;
   }

   /**
    *  reads json file and grab all id before delete test spaces.
    *  @return string  
    */
   private function getAllSpaceId()
   {
   	$input=$this->readFromLocalFile();
   	$tempArray = json_decode($input);
   	$spaceId = [];
   	foreach($tempArray as $key => $array)
   	{
   		if($array->type == 'space')
   		{
   			$spaceId[] = $array->id;
   		}
   	}
   	return $spaceId;
   }
}
