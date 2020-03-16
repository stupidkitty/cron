<?php
namespace SK\CronModule\Executor;

use Psr\EventDispatcher\EventDispatcherInterface;
use SK\CronModule\Event\Events;
use SK\CronModule\Event\TaskExecutionEvent;
use SK\CronModule\Handler\HandlerFactoryInterface;
use SK\CronModule\Model\TaskInterface;
use SK\CronModule\Scheduler\SchedulerInterface;

/**
 * Executes scheduled tasks.
 */
class ScheduledExecutor implements ScheduledExecutorInterface
{
    /**
     * @var SchedulerInterface
     */
    private $scheduler;
    /**
     * @var HandlerFactoryInterface
     */
    private $handlerFactory;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var DateTimeImmutable
     */
    private $executionDateTime;

    /**
     * @param SchedulerInterface $scheduler
     * @param HandlerFactoryInterface $handlerFactory
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        SchedulerInterface $scheduler,
        HandlerFactoryInterface $handlerFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->scheduler = $scheduler;
        $this->handlerFactory = $handlerFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->executionDateTime = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->executionDateTime = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        foreach ($this->scheduler->getScheduled() as $task) {
            $this->execute($task);
        }
    }

    /**
     * Run execution with given handler.
     *
     * @param TaskInterface $task
     *
     * @param TaskExecutionInterface $execution
     */
    public function execute(TaskInterface $task)
    {
        $task->last_execution = $this->executionDateTime->format('Y-m-d H:i:s');
        $task->setStatus(TaskInterface::STATUS_RUNNING);
        $task->save();

        $start = \microtime(true);

        try {
            $handler = $this->handlerFactory->create($task->handler);
            $handler->run();

            $this->hasPassed($task);
        } catch (\Throwable $e) {
            $this->hasFailed($task, $e);
        } finally {
            $this->finalize($task, $start);
        }
    }

    /**
     * The given task passed the run.
     *
     * @param TaskInterface $task
     */
    private function hasPassed(TaskInterface $task)
    {
        $task->setStatus(TaskInterface::STATUS_COMPLETED);

        $this->eventDispatcher->dispatch(
            new TaskExecutionEvent($task->task_id, TaskExecutionEvent::TYPE_PASSED),
            Events::TASK_PASSED,
        );
    }

    /**
     * The given task failed the run.
     *
     * @param TaskInterface $task
     * @param \Throwable $exception
     */
    private function hasFailed(TaskInterface $task, \Throwable $e)
    {
        // this find is necessary if the storage because the storage could be
        // invalid (clear in doctrine) after handling an execution.
        $task->setStatus(TaskInterface::STATUS_FAILED);

        $this->eventDispatcher->dispatch(
            new TaskExecutionEvent($task->task_id, TaskExecutionEvent::TYPE_FAILED),
            Events::TASK_FAILED,
        );
    }

    /**
     * Finalizes given execution.
     *
     * @param TaskInterface $task
     * @param int $startedAt
     */
    private function finalize(TaskInterface $task, $startedAt)
    {
        $task->duration = (\microtime(true) - $startedAt);
        $task->save();

        $this->eventDispatcher->dispatch(
            new TaskExecutionEvent($task->task_id, TaskExecutionEvent::TYPE_FINISHED),
            Events::TASK_FINISHED,
        );
    }
}
