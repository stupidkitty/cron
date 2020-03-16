<?php
namespace SK\CronModule\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class TaskExecutionEvent
 */
class TaskExecutionEvent extends Event
{
    const TYPE_PASSED = 'passed';
    const TYPE_FAILED = 'failed';
    const TYPE_FINISHED = 'finished';

    protected $taskId;
    protected $type;

    /**
     * TaskExecutionEvent constructor
     *
     * @param integer $taskId
     */
    public function __construct(int $taskId, string $type)
    {
        $this->taskId = $taskId;
        $this->type = $type;
    }

    /**
     * Get the value of taskId
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }
}
