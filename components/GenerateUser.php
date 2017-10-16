<?php

namespace humhub\modules\performance_insights\components;
use Yii;
use humhub\modules\performance_insights\interfaces\TestInterface;
use humhub\modules\user\models\forms\Registration;
use humhub\modules\user\models\User;
use humhub\modules\user\models\GroupUser;
use humhub\modules\user\models\Profile;
use humhub\modules\user\models\Password;

class GenerateUser extends BaseTest implements TestInterface 
{

	public  $number;
	private $user_name_prefix='TEST_USER';
	private $faker;
	private $registration;
	private $userId;
	private $data=array();

	public function __construct($number=false)
	{		
		$this->number=$number;
		$this->faker = \Faker\Factory::create();
		parent::__construct('test_history.json');
	}
	public function generateData()
	{
		for($i=0;$i<$this->number;$i++)
		{
			$user=$this->generateUser();		
			$profile=$this->generateProfile();
			//$password=$this->generatePassword();
		}
		$this->writeToLocalFile($this->data);	
		return true;		
	}

	public function deleteData()
	{		
		$arrayId=$this->getAllUserId($this->readFromLocalFile());
		foreach($arrayId as $id)
		{
			$user=User::find()->where(['id'=>$id])->one();
			if($user)
			{
				$user->delete();
			}
		}
		$this->deleteSelectedLocalItems('user');
	}

	private function generateUser()
	{
		$user=new User();
		$user->scenario = 'registration';
		$user->username=$this->user_name_prefix.'_'.$this->faker->randomLetter.$this->faker->randomLetter.$this->faker->randomLetter.'_'.time();

		$user->email=time().$this->faker->unique()->email;
		$user->language = Yii::$app->language;
		$user->status = User::STATUS_ENABLED;
		$this->userId=$user->save(false)?$user->id:false;
		$this->data[]=array('id'=>$user->id,'type'=>'user');
		return true;

	}	

	private function generateProfile()
	{
		$profile=new Profile();
		$profile->firstname=$this->user_name_prefix.'_'.$this->faker->firstName;
		$profile->lastname=$this->faker->lastName;
		$profile->user_id = $this->userId;
		$profile->save(false);
		return true;

	}

	private function generateGroup()
	{
		$groupUser=new GroupUser();
		$groupUser->group_id=$this->generateGroupId();
		$groupUser->user_id = $this->userId;
		$groupUser->save();
		return true;

	}

	private function generatePassword()
	{
		$generatedPwd=$this->faker->password;
		$password=new Password();
		$password->newPassword=$generatedPwd;
		$password->newPasswordConfirm=$generatedPwd;
		$password->user_id = $this->userId;
		$password->setPassword($generatedPwd);
		$password->save(false);
		return true;		
	}

	private function getAllUserId()
	{
		$input=$this->readFromLocalFile();
		$tempArray =json_decode($input);
		$userId=[];
		foreach($tempArray as $key=>$array)
		{
			if($array->type=='user')
			{
				$userId[]=$array->id;
			}
		}
		return $userId;
	}
}