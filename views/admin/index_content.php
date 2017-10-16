<?php
use yii\helpers\Html;
use yii\helpers\Url;
humhub\modules\performance_insights\Assets::register($this);
?>

<!-- Bootstrap alert message -->


<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="alert alert-success alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?= Yii::$app->session->getFlash('success') ?>
	</div>
<?php endif; ?>


<!-- Button groups for generating space -->

<label><?php echo Yii::t('PerformanceInsightsModule.base', 'Generate fake data for testing.');  ?></label>
<hr>

<label class="label-margin"><?php echo Yii::t('PerformanceInsightsModule.base', 'Generate Spaces') ?></label>
<div class="btn_group">
	<button type="button" class="btn btn-default apply_margin" data-value="5" data-type="space">5</button>
	<button type="button" class="btn btn-default apply_margin" data-value="10" data-type="space">10</button>
	<button type="button" class="btn btn-default apply_margin" data-value="20" data-type="space">20</button>
	<button type="button" class="btn btn-default apply_margin" data-value="5000" data-type="space">5000</button>
	<?php if($isDeleteSpaceButtonHidden): ?>
		<button type="button" class="btn btn-danger delete_test_space" data-url="<?php echo Url::to(['/performance_insights/admin/delete']); ?>" data-type="space"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;<?php echo  Yii::t('PerformanceInsightsModule.base', 'Delete All Test Spaces') ?></button>
	<?php endif; ?>
</div>

<!-- Button groups for generating user -->

<label class="label-margin"><?php echo Yii::t('PerformanceInsightsModule.base', 'Generate Users') ?></label>
<div class="btn_group">
	<button type="button" class="btn btn-default apply_margin" data-value="50" data-type="user">50</button>
	<button type="button" class="btn btn-default apply_margin" data-value="100" data-type="user">100</button>
	<button type="button" class="btn btn-default apply_margin" data-value="500" data-type="user">500</button>
	<button type="button" class="btn btn-default apply_margin" data-value="5000" data-type="user">5000</button>
	<?php if($isDeleteUserButtonHidden): ?>
		<button type="button" class="btn btn-danger delete_test_user" data-url="<?php echo Url::to(['/performance_insights/admin/delete']); ?>" data-type="user"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp; <?php echo Yii::t('PerformanceInsightsModule.base', 'Delete All Test Users') ?></button>
	<?php endif; ?>
</div>

<?= \humhub\modules\performance_insights\widgets\LoadingMenu::widget(['id'=>'loading-img']); ?>

<!-- Hidden form that submit for each button click -->

<?= \yii\helpers\Html::beginForm(['/performance_insights/admin/generate'],'post',['id'=>'frontend-form']) ?>
<input type='hidden' id="test-type" name='type' style="display:none;">
<input type='hidden' id="test-quantity" name='number' style="display:none;">
<input type='submit' name='submit' style="display:none;">
<?= \yii\helpers\Html::endForm() ?>


<!-- initialize script -->

<script>
	$(document).ready(function(){
		var baseUrl="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['/']) ?>";	
		performace_insight.init(baseUrl);
	})	
</script>