<?php
namespace humhub\modules\performance_insights\components;
use Yii;
use humhub\modules\performance_insights\interfaces\TestInterface;
use humhub\modules\user\models\forms\Registration;
use humhub\modules\user\models\User;
use humhub\modules\user\models\GroupUser;
use humhub\modules\user\models\Profile;
use humhub\modules\user\models\Password;
/*
 *  Manage User Testing
 */
class GenerateUser extends BaseTest implements TestInterface 
{

	public  $number;
	private $user_name_prefix='TEST_USER';
	private $faker;
	private $registration;
	private $userId;
	private $data=array();
	private $userNameCounter;
	private $emailCounter;

	public function __construct($number=false)
	{		
		$this->number=$number;
		$this->faker = \Faker\Factory::create();
		$this->userNameCounter=0;
		$this->emailCounter=0;
		parent::__construct('test_history.json');		
	}
	/*
     *  Generate Faker Users.
     *  @return bool  
     */
	public function generateData()
	{
		for($i=0;$i<$this->number;$i++)
		{
			$user=$this->generateUser();		
			$profile=$this->generateProfile();			
		}
		$this->writeToLocalFile($this->data);	
		return true;		
	}
   /*
    *  Remove Faker Generated Users.
    */
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
   /*
    *  Insert record into user Table.
    *  @return bool  
    */
   private function generateUser()
   {
   	$user=new User();
   	$user->scenario = 'registration';
   	$user->username=$this->user_name_prefix.'_'.time().++$this->userNameCounter;
   	$user->email=time().++$this->emailCounter.$this->faker->unique()->email;
   	$user->language = Yii::$app->language;
   	$user->status = User::STATUS_ENABLED;
   	$this->userId=$user->save(false)?$user->id:false;
   	$this->data[]=array('id'=>$user->id,'type'=>'user');
   	return true;
   }	
   /*
    *  Inserts record into Profile Table.
    *  @return bool  
    */
   private function generateProfile()
   {
   	$profile=new Profile();
   	$profile->firstname=$this->user_name_prefix.'_'.$this->faker->firstName;
   	$profile->lastname=$this->faker->lastName;
   	$profile->user_id = $this->userId;
   	$profile->save(false);
   	return true;
   }

   /*
    *  reads json file and grab all user id before delete test users.
    *  @return string  
    */
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