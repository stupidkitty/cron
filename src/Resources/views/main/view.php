<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('cron', 'cron');
$this->params['subtitle'] = Yii::t('cron', 'info');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('cron', 'cron'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = Yii::t('cron', 'info');

?>

<div class="box box-default">
    <div class="box-header with-border">
    <i class="fa fa-info-circle text-blue"></i><h3 class="box-title"><?= Yii::t('cron', 'info') ?></h3>
        <div class="box-tools pull-right">
            <div class="btn-group">
                <?= Html::a('<i class="fa fa-fw fa-plus text-green"></i> ' . Yii::t('cron', 'add'), ['create'], ['class' => 'btn btn-default btn-sm']) ?>
                <?= Html::a('<i class="fa fa-fw fa-edit text-blue"></i> ' . Yii::t('cron', 'edit'), ['update', 'id' => $task->getId()], ['class' => 'btn btn-default btn-sm']) ?>
                <?= Html::a('<i class="fa fa-fw fa-trash text-red"></i> ' . Yii::t('cron', 'delete'), ['delete', 'id' => $task->getId()], [
                    'class' => 'btn btn-default btn-sm',
                    'data' => [
                        'confirm' => Yii::t('cron', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>

    <div class="box-body pad">
        <?= DetailView::widget([
            'model' => $task,
            'attributes' => [
                'task_id',
                'expression',
                [
                    'attribute' => 'handler',
                    'value' => function ($task) {
                        return Html::tag('code', $task->handler);
                    },
                    'format' => 'html',
                ],
                'priority',
                'enabled',
                [
                    'attribute' => 'last_execution',
                    'format' => ['datetime', 'php:d M Y H:i:s'],
                ],
                [
                    'attribute' => 'duration',
                    'label' => 'Duration, ms',
                ],
                'status',
            ],
        ]) ?>
    </div>

    <div class="box-footer">
        <?= Html::a(Yii::t('cron', 'to_list'), ['index'], ['class' => 'btn btn-warning']) ?>
    </div>
</div>
