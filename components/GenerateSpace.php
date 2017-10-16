<?php

namespace humhub\modules\performance_insights\components;
use Yii;
use humhub\modules\performance_insights\interfaces\TestInterface;
use humhub\modules\performance_insights\components\BaseTest;
use humhub\modules\space\models\Space;

class GenerateSpace extends BaseTest implements TestInterface 
{

	public  $number;
	private $space_name_prefix='TEST_SPACE';
	private $faker;
	private $registration;
	private $userId;

	public function __construct($number=false)
	{
		$this->number=$number;
		$this->faker = \Faker\Factory::create();
		parent::__construct('test_history.json');
	}

	public function generateData()
	{
		$data=array();
		for($i=0;$i<$this->number;$i++)
		{
			$model=$this->createSpaceModel();
			$model->name = $this->space_name_prefix.'_'.$this->faker->randomLetter.'_'.time();
			$model->description = $this->faker->text($maxNbChars = 45);
			$model->color = '#d1d1d1';
			$model->save(false);
			$data[]=array('id'=>$model->id,'type'=>'space');
		}		
		$this->writeToLocalFile($data);	
		return true;			
	}

	public function deleteData(){
		$arrayId=$this->getAllSpaceId($this->readFromLocalFile());		
		foreach($arrayId as $id)
		{
			$space=Space::find()->where(['id'=>$id])->one();
			if($space)
			{
				$space->delete();
			}
		}
		$this->deleteSelectedLocalItems('space');
	}

	protected function createSpaceModel() 
	{
		$model = new Space();
		$model->scenario = 'create';
		$model->visibility = Yii::$app->getModule('space')->settings->get('defaultVisibility');
		$model->join_policy = Yii::$app->getModule('space')->settings->get('defaultJoinPolicy');
		return $model;
	}

	private function getAllSpaceId()
	{
		$input=$this->readFromLocalFile();
		$tempArray =json_decode($input);
		$spaceId=[];
		foreach($tempArray as $key=>$array)
		{
			if($array->type=='space')
			{
				$spaceId[]=$array->id;
			}
		}
		return $spaceId;
	}
}