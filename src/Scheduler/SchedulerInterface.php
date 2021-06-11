<?php
namespace SK\CronModule\Scheduler;

use SK\CronModule\Model\TaskInterface;

/**
 * Interface for task-scheduler.
 */
interface SchedulerInterface
{
    /**
     * Ged scheduled tasks collection
     *
     * @return array
     */
    public function getScheduled(): array;

    /**
     * Schedule the task
     *
     * @param TaskInterface $task
     * @return void
     */
    public function schedule(TaskInterface $task): void;
}
