<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('cron', 'cron');
$this->params['subtitle'] = Yii::t('cron', 'new_task');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('cron', 'cron'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = Yii::t('cron', 'new_task');

?>

<div class="box box-default">
    <div class="box-header with-border">
    <i class="fa fa-plus"></i><h3 class="box-title"><?= Yii::t('cron', 'new_task') ?></h3>
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
        <?= Html::submitButton(Yii::t('cron', 'add'), ['class' => 'btn btn-default', 'form' => 'task-form']) ?>
        <?= Html::a(Yii::t('cron', 'to_list'), ['index'], ['class' => 'btn btn-warning']) ?>
    </div>
</div>
