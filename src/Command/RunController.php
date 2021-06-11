<?php

namespace SK\CronModule\Command;

use SK\CronModule\Executor\ScheduledExecutorInterface;
use yii\console\Controller;

/**
 * Class RunController
 *
 * @package SK\CronModule\Command
 */
class RunController extends Controller
{
    /**
     * @param ScheduledExecutorInterface $sheduledExecutor
     */
    public function actionIndex(ScheduledExecutorInterface $sheduledExecutor)
    {
        $sheduledExecutor->run();
    }
}
