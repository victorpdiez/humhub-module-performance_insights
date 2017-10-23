<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
humhub\modules\performance_insights\Assets::register($this);
/* @var $this yii\web\View */
/* @var $searchModel humhub\modules\performance_insights\models\PerformanceTestHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="alert flash-msg">
  <span class="action-msg"></span>
</div>


<?php
$this->title = Yii::t('PerformanceInsightsModule.base', "Performance Test Histories");
$this->params['breadcrumbs'][] = $this->title;
echo Html::dropDownList('listname', 0, ['0'=>Yii::t('PerformanceInsightsModule.base', "Delete Selected")],['class'=>'form-control','id'=>'performance-actions','style'=>'display:none;']);
echo Html::Button(Yii::t('PerformanceInsightsModule.base', "Delete Selected"), ['class'=> 'btn btn-danger','id'=>'performance-action-button']) ;
?>

<br/>



<div class="performance-test-history-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(['id' => 'pjax-grid-view']); ?>   
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover table-history'],
        'filterModel' => $searchModel,
        'columns' => [
             ['class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => ['style'=>'margin-bottom: 0px;']
            ],
            [
                'attribute' => 'url',
                'format' => 'url'
            ],
            [
                'attribute' => 'page_load_time',
                'content' => function($data) {
                    return $data->page_load_time.'s';
                },
            ],
            [    
                'attribute' => 'report_time',
                'content' => function($data) {
                    return date('d-m-Y H:i:sa', $data->report_time);
                },
                'filterInputOptions' => [
                    'class' => 'form-control input-datepicker', 
                    'id' => null
                ],
            ]


        ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>  


    <script>

        $(document).ready(function(){
            var baseUrl="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['/']) ?>";
            performace_history.init(baseUrl);
        })

    </script>