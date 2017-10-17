<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
humhub\modules\performance_insights\Assets::register($this);
$selectDropDown='';
foreach($urls as $key=>$url){
	$selectDropDown.='<option value="'.$key.'">'.ucfirst($url).'</option>';	
}

?>


<ul class="nav nav-tabs">	
	<li class="nav active"><a href="#A" data-toggle="tab" aria-expanded="true"><?php echo Yii::t('PerformanceInsightsModule.base', 'Page') ?></a></li>
	<li class="nav"><a href="#B" data-toggle="tab" aria-expanded="false"><?php echo Yii::t('PerformanceInsightsModule.base', 'Search') ?></a></li>
</ul>

<div class="alert alert-info" id="redirect-message" style="display:none;">
	<?php echo Yii::t('PerformanceInsightsModule.base', 'URL contain redirects.Please enter valid URL') ?>.
</div>
<div class="tab-content">
	<div class="tab-pane fade active in" id="A">
		<!-- Form for testing url -->
		<?php $form = ActiveForm::begin(['method'=>'post','action' => Url::to(['admin/test']),'id'=>'pi-form']); ?>



		<?= $form->field($performaceTestForm, 'url', [
			'template' => '<div class="input-group">
			{input}
			<div class="help-block" style="color:red !important;"></div>
			<span class="input-group-btn">
			<button class="btn btn-primary" type="Submit" id="admin-test">'.Yii::t('PerformanceInsightsModule.base', 'Analyze').'</button>
			</span>
			</div>',
			])->input('text',['placeholder' => Yii::t('PerformanceInsightsModule.base', 'Enter URL to Analyze...')]); ?>


			<?php ActiveForm::end(); ?>
			<div class="row">
				<div id="performace-result" style="display:none;">
					<div class="" style="display:none;">
						<div id="site-screenshot"></div>			
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

						<p id="pr-label"><b><?php echo Yii::t('PerformanceInsightsModule.base', 'Latest Performance Report For') ?></b></p>
						<p id="site-id"></p>
						<hr>	

						<p id="report-generated-id"><?php echo Yii::t('PerformanceInsightsModule.base', 'Report Generated:') ?><span id="report-generated-time"></span></p>
						<div id="page-details">
							<span><?php echo Yii::t('PerformanceInsightsModule.base', 'Fully Loaded Time') ?></span>
							<span id="page-load-time" class="Count"> </span>								
						</div>

					</div>

				</div>
				<?= \humhub\modules\performance_insights\widgets\LoadingMenu::widget(['id'=>'loading-img']); ?>
				
			</div>
		</div>
		<div class="tab-pane fade" id="B">

			<h5><?php echo Yii::t('PerformanceInsightsModule.base', 'Find Directory Search Performance') ?></h5>
			<?php $form = ActiveForm::begin(['method'=>'post','action' => Url::to(['admin/test-search']),'id'=>'pi-search-form']); ?>

			<?= $form->field($performaceSearchForm, 'keyword', [
				'template' => '<div class="input-group">
				<input type="text" id="performacesearchform-keyword" class="form-control" name="PerformaceSearchForm[keyword]" aria-required="true" aria-invalid="true" placeholder="'.Yii::t('PerformanceInsightsModule.base', 'Enter search keyword...').'">
				<select id="performacesearchform-searchurl" class="form-control" name="PerformaceSearchForm[searchUrl]" aria-required="true" aria-invalid="false">'.
				$selectDropDown.'
				</select>
				<div class="help-block" style="color:red !important;"></div>
				<span class="input-group-btn">
				<button type="button" id="perform-auto-search" class="btn btn-primary">'.Yii::t('PerformanceInsightsModule.base', 'Start Test').'</button>
				</span>
				</div>',
				]); ?>

				<?php ActiveForm::end(); ?>

				<div id="search-performance-result" style="display:none;">
					<label><?php echo Yii::t('PerformanceInsightsModule.base', 'Page Details') ?></label>
					<div class="page-details-group">
						<div class="page-details_cpy page-details">
							<span><?php echo Yii::t('PerformanceInsightsModule.base', 'Average Loaded Time') ?></span>
							<span class="ind-item Count"> </span>

						</div>
					</div>
				</div>
				<?= \humhub\modules\performance_insights\widgets\LoadingMenu::widget(['id'=>'loading-img-2']); ?>
				
			</div>
		</div>
		<script>
			var baseUrl="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['/']) ?>";
			performace_insight.init(baseUrl);

		</script>	