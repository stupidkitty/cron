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
    public function getId(): ?int;

    /**
     * Returns task handler.
     *
     * @return string
     */
    public function getHandler(): string;

    /**
     * Returns last-execution datetime.
     *
     * @return string
     */
    public function getLastExecution(): string;

    /**
     * Get status value
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set status value
     *
     * @param string $status
     * @return void
     */
    public function setStatus(string $status);

    /**
     * Save method (instance of active record need)
     *
     * @return void
     */
    public function save();
}
