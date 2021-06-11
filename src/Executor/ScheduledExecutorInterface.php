<?php

namespace SK\CronModule\Executor;

/**
 * Interface for task-executor.
 */
interface ScheduledExecutorInterface
{
    /**
     * Run scheduled tasks.
     */
    public function run(): void;
}
