<?php
namespace humhub\modules\performance_insights\interfaces;
/*
 *  Makes sure all test contain generate and delete data methods.
 */
interface TestInterface 
{
	public function generateData();

	public function deleteData();
}

?>