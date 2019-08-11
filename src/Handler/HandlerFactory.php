<?php
namespace SK\CronModule\Handler;

use SK\CronModule\Model\TaskInterface;

class HandlerFactory
{
    /**
     * Create handler by model
     *
     * @param TaskInterface $task
     * @return HandlerInterface
     */
    public static function create(TaskInterface $task)
    {
        return new $task->handler;
    }
}
