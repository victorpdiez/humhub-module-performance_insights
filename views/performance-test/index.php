<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel humhub\modules\performance_insights\models\PerformanceTestHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Performance Test Histories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="performance-test-history-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Performance Test History', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'url:ntext',
            'page_load_time:datetime',
            'report_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
