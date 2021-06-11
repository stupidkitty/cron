<?php

namespace SK\CronModule\Scheduler;

use Cron\CronExpression;
use SK\CronModule\Model\Task;
use SK\CronModule\Model\TaskInterface;

/**
 * Class TaskScheduler
 *
 * @package SK\CronModule\Scheduler
 */
class TaskScheduler implements SchedulerInterface
{
    /**
     * @var array
     */
    private array $scheduledTasks = [];

    /**
     * Получить все запланированные таски на этот период
     *
     * @return array
     */
    public function getScheduled(): array
    {
        $tasks = Task::find()
            ->where(['enabled' => 1])
            ->orderBy(['priority' => SORT_ASC])
            ->all();

        foreach ($tasks as $task) {
            $this->schedule($task);
        }

        return $this->scheduledTasks;
    }

    /**
     * Добавить таску в запланированные.
     *
     * @param TaskInterface $task
     * @return self
     */
    public function addTask(TaskInterface $task): self
    {
        $task->setStatus(TaskInterface::STATUS_PLANNED);
        $task->save();

        $this->scheduledTasks[] = $task;

        return $this;
    }

    /**
     * Запланировать таску, если время подошло.
     *
     * @param TaskInterface $task
     * @return void
     */
    public function schedule(TaskInterface $task): void
    {
        try {
            $currentTime = new \DateTime('now', new \DateTimeZone('UTC'));

            if ($task->last_execution === null || $task->last_execution === '') {
                $this->addTask($task);

                return;
            }

            $cron = new CronExpression($task->expression);
            $lastExecution = \DateTime::createFromFormat('Y-m-d H:i:s', $task->last_execution, new \DateTimeZone('UTC'));
            $nextRunTime = $cron->getNextRunDate($lastExecution, 0, false, 'UTC');

            if ($currentTime >= $nextRunTime) {
                $this->addTask($task);
            }
        } catch (\Throwable $e) {
            echo 'Task cannot be scheduled: ' . $e->getMessage() . PHP_EOL;
        }
    }
}
