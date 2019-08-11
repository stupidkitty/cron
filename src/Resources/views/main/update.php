<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('cron', 'cron');
$this->params['subtitle'] = Yii::t('cron', 'edit');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('cron', 'cron'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = Yii::t('cron', 'edit');

?>

<div class="box box-default">
    <div class="box-header with-border">
        <i class="fa fa-edit"></i><h3 class="box-title"><?= Yii::t('cron', 'edit_task') ?></h3>
        <div class="box-tools pull-right">
            <div class="btn-group">
                <?= Html::a('<i class="fa fa-fw fa-plus text-green"></i> ' . Yii::t('cron', 'add'), ['create'], ['class' => 'btn btn-default btn-sm']) ?>
                <?= Html::a('<i class="fa fa-fw fa-info-circle text-blue"></i> ' . Yii::t('cron', 'info'), ['view', 'id' => $task->getId()], ['class' => 'btn btn-default btn-sm']) ?>
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
        <?php $activeForm = ActiveForm::begin([
            'id' => 'task-form',
        ]) ?>

            <?= $activeForm->field($form, 'handler')->textInput() ?>

            <?= $activeForm->field($form, 'expression')->textInput() ?>

            <?= $activeForm->field($form, 'priority')->textInput() ?>

            <?= $activeForm->field($form, 'enabled')->checkbox() ?>

        <?php ActiveForm::end() ?>
    </div>

    <div class="box-footer">
        <?= Html::submitButton(Yii::t('cron', 'save'), ['class' => 'btn btn-default', 'form' => 'task-form']) ?>
        <?= Html::a(Yii::t('cron', 'to_list'), ['index'], ['class' => 'btn btn-warning']) ?>
    </div>
</div>
