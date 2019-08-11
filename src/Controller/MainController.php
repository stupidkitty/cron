<?php
namespace SK\CronModule\Controller;

use Yii;

use yii\web\Request;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use SK\CronModule\Model\Task;
use SK\CronModule\Executor\TaskExecutor;
/**
 * MainController implements the CRUD actions for Task model.
 */
class MainController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
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
     * @param \yii\base\Action $action
     *
     * @return bool
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['exec-task'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Task models.
     *
     * @return mixed
     */
    public function actionIndex($page = 0)
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
     *
     * @return mixed
     */
    public function actionView($id)
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
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$container->get(Request::class);
        $model = new Task;

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
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$container->get(Request::class);
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
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findById($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Выполняет выбранную задачу немедленно.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionExecTask()
    {
        $request = Yii::$container->get(Request::class);

        try {
            $id = (int) $request->post('task_id', 0);
            $task = $this->findById($id);
        } catch (NotFoundHttpException $e) {
            return $this->asJson([
                'error' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]
            ]);
        }

        $taskExecutor = new TaskExecutor();
        $taskExecutor->run($task);

        return $this->asJson([
            'message' => 'Success',
            'result' => $task->result,
        ]);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Task the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findById($id)
    {
        $task = Task::findOne($id);

        if (null === $task) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $task;
    }
}
