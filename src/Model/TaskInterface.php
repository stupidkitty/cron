<?php
namespace SK\CronModule\Model;

/**
 * Interface for task.
 */
interface TaskInterface
{
    const STATUS_PLANNED = 'planned'; // 'planned'
    const STATUS_RUNNING = 'running'; // 'running'
    const STATUS_COMPLETED = 'completed'; // 'completed'
    const STATUS_FAILED = 'failed'; // 'failed'
    const STATUS_ABORTED = 'aborted'; // 'aborted'

    /**
     * Returns task-id.
     *
     * @return null|integer
     */
    public function getId();

    /**
     * Returns task handler.
     *
     * @return string
     */
    public function getHandler();

    /**
     * Returns last-execution datetime.
     *
     * @return string
     */
    public function getLastExecution();
}
