<?php
namespace SK\CronModule\Command;

use SK\CronModule\Executor\ScheduledExecutorInterface;
use Yii;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 */
class RunController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $sheduledExecutor = $this->get(ScheduledExecutorInterface::class);
        $sheduledExecutor->run();
    }

    private function get($name)
    {
        return Yii::$container->get($name);
    }
}
