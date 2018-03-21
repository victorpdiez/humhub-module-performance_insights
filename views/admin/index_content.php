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

<label><?= Yii::t('PerformanceInsightsModule.base', 'Generate fake data for testing.');  ?></label>
<hr>

<label class="label-margin"><?= Yii::t('PerformanceInsightsModule.base', 'Generate Spaces') ?></label>
<div class="btn_group">
	<button type="button" class="btn btn-default apply_margin" data-value="5" data-type="space">500</button>
	<button type="button" class="btn btn-default apply_margin" data-value="1000" data-type="space">1k</button>
	<button type="button" class="btn btn-default apply_margin" data-value="5000" data-type="space">5k</button>
	<button type="button" class="btn btn-default apply_margin" data-value="10000" data-type="space">10k</button>
	<button type="button" class="btn btn-default apply_margin" data-value="20000" data-type="space">20k</button>
	<button type="button" class="btn btn-default apply_margin" data-value="50000" data-type="space">50k&nbsp;(<?= Yii::t('PerformanceInsightsModule.base', 'not recommended') ?>)</button>

	<?php if($isDeleteSpaceButtonHidden): ?>
		<button type="button" class="btn btn-danger delete_test_space" data-url="<?= Url::to(['/performance_insights/admin/delete']); ?>" data-type="space"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;<?=  Yii::t('PerformanceInsightsModule.base', 'Delete All Test Spaces') ?></button>
	<?php endif; ?>
</div>

<!-- Button groups for generating user -->

<label class="label-margin"><?= Yii::t('PerformanceInsightsModule.base', 'Generate Users') ?></label>
<div class="btn_group">
	<button type="button" class="btn btn-default apply_margin" data-value="10" data-type="user">10k</button>
	<button type="button" class="btn btn-default apply_margin" data-value="50000" data-type="user">50k</button>
	<button type="button" class="btn btn-default apply_margin" data-value="100000" data-type="user">100k</button>
	<button type="button" class="btn btn-default apply_margin" data-value="500000" data-type="user">500k</button>
	<button type="button" class="btn btn-default apply_margin" data-value="1000000" data-type="user">1m&nbsp;(<?= Yii::t('PerformanceInsightsModule.base', 'not recommended')?>)</button>
	<?php if($isDeleteUserButtonHidden): ?>
		<button type="button" class="btn btn-danger delete_test_user" data-url="<?= Url::to(['/performance_insights/admin/delete']); ?>" data-type="user"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp; <?php echo Yii::t('PerformanceInsightsModule.base', 'Delete All Test Users') ?></button>
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
