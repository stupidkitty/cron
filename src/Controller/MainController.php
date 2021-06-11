<?php

namespace SK\CronModule\Controller;

use SK\CronModule\Executor\ScheduledExecutorInterface;
use SK\CronModule\Model\Task;
use Throwable;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;

/**
 * MainController implements the CRUD actions for Task model.
 */
class MainController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @param Action $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (in_array($action->id, ['exec-task'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Task models.
     *
     * @param int $page
     * @return string
     */
    public function actionIndex(int $page = 0): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Task::find(),
            'sort' => [
                'defaultOrder' => ['priority' => SORT_ASC],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'page' => $page,
        ]);
    }

    /**
     * Displays a single Task model.
     *
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $task = $this->findById($id);

        return $this->render('view', [
            'task' => $task,
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|Response
     */
    public function actionCreate(Request $request)
    {
        $model = new Task();

        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'form' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param Request $request
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionUpdate(Request $request, int $id)
    {
        $task = $this->findById($id);

        if ($task->load($request->post()) && $task->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'form' => $task,
                'task' => $task,
            ]);
        }
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return Response
     */
    public function actionDelete(int $id): Response
    {
        try {
            $this->findById($id)->delete();
        } catch (StaleObjectException | NotFoundHttpException | Throwable $e) {
        }

        return $this->redirect(['index']);
    }

    /**
     * Выполняет выбранную задачу немедленно.
     *
     * @param Request $request
     * @param ScheduledExecutorInterface $sheduledExecutor
     * @return Response
     */
    public function actionExecTask(Request $request, ScheduledExecutorInterface $sheduledExecutor): Response
    {
        try {
            $id = (int) $request->post('task_id', 0);
            $task = $this->findById($id);
        } catch (NotFoundHttpException $e) {
            return $this->asJson([
                'error' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ],
            ]);
        }

        $sheduledExecutor->execute($task);

        return $this->asJson([
            'message' => 'Success',
        ]);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findById(int $id): Task
    {
        $task = Task::findOne($id);

        if (null === $task) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $task;
    }
}
