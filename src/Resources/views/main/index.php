<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

$this->title = Yii::t('cron', 'cron');
$this->params['subtitle'] = Yii::t('cron', 'overview');

if ($page <= 1) {
    $tableTitle = Yii::t('cron', 'tasks');

    $this->params['breadcrumbs'][] = Yii::t('cron', 'cron');
} else {
    $tableTitle = Yii::t('cron', 'text_with_page_num', [
        'text' => Yii::t('cron', 'tasks'),
        'page' => $page,
        'separator' => '-'
    ]);

    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('cron', 'tasks'),
        'url' => ['index'],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('cron', 'page_num', ['page' => $page]),
    ];
}

?>

<div class="box box-default">
    <div class="box-header with-border">
        <i class="fa fa-list"></i><h3 class="box-title"><?= $tableTitle ?></h3>
        <div class="box-tools pull-right">
            <div class="btn-group">
                <?= Html::a('<i class="fa fa-fw fa-plus text-green"></i> ' . Yii::t('cron', 'add'), ['create'], ['class' => 'btn btn-default btn-sm']) ?>
            </div>
        </div>
    </div>

    <div class="box-body pad">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
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
                    'label' => 'Dur., ms',
                    'value' => function ($task) {
                        return round($task->duration * 1000, 2);
                    },
                ],
                'status',
                [
                    'label' => 'Execute',
                    'value' => function ($task) {
                        return Html::button('<i class="fa fa-fw fa-play text-red"></i> Run', [
                            'class' => 'btn btn-sm btn-default',
                            'data-id' => $task->getId(),
                            'data-action' => 'exec-task',
                            'data-url' => Url::toRoute(['exec-task']),
                        ]);
                    },
                    'format' => 'raw',
                ],
                [
                    'class' => ActionColumn::class,
                    'options' => [
                        'width' => 75,
                    ]
                ],
            ],
        ]) ?>
    </div>
</div>

<?php

$js = <<< 'JAVASCRIPT'
   let taskRunButtons = document.querySelectorAll('button[data-action="exec-task"');

   taskRunButtons.forEach(function (runButton) {
       runButton.addEventListener('click', function (event) {
           event.preventDefault();
           event.stopPropagation();

            let taskId = this.getAttribute('data-id');
            let actionUrl = this.getAttribute('data-url');
			let formData = new FormData();

            formData.append('task_id', taskId);

            fetch(actionUrl, {
				method: 'POST',
				body: formData,
				credentials: 'same-origin'
			})
			.then((response) => {
				if (!response.ok) {
					throw new Error(response.statusText);
				}

				return response;
			})
			.then(response => response.json())
			.then(function (response) {
				if (undefined !== response.error) {
					throw new Error(response.error.message);
				}

				toastr.success(response.message);
			})
			.catch(function(error) {
				toastr.error(error.message);
			});
       });
   });
JAVASCRIPT;

$this->registerJS($js, \yii\web\View::POS_READY);
