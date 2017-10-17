<?php 
use humhub\modules\performance_insights\widgets\ModelPopup;
$this->beginContent('@admin/views/layouts/main.php') ?>
<div class="panel panel-default">	
	<div class="panel-heading"><h1><strong><?php echo Yii::t('PerformanceInsightsModule.base', 'Performance Insights'); ?></strong></h1></div>
	<span class="module-desc-title"><?php echo Yii::t('PerformanceInsightsModule.base', 'Analyze your Humhub site’s speed');  ?></span>
	<?= \humhub\modules\performance_insights\widgets\TabMenu::widget(); ?>
	<div class="panel-body panel-body-min-height">
		<?php echo $content; ?>
	</div>
	<?= ModelPopup::widget();  ?>
</div>
<?php $this->endContent(); ?>