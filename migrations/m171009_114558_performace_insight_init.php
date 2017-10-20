<?php

use yii\db\Migration;

class m171009_114558_performace_insight_init extends Migration
{
	public function safeUp()
	{
        $tableSchema = Yii::$app->db->schema->getTableSchema('performance_test_history');
        if ($tableSchema == null) {
           $this->createTable('performance_test_history', array(
              'id' => 'pk',
              'url' => 'Text',
              'page_load_time' => 'int(11) NOT NULL',
              'report_time'=> 'int(11) NOT NULL'
          ), '');
       }
   }

   public function safeDown()
   {
      echo "m171009_114558_performace_insight_init cannot be reverted.\n";

      return false;
  }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171009_114538_performace_insight_init cannot be reverted.\n";

        return false;
    }
    */
}
