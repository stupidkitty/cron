<?php
namespace SK\CronModule\Handler;

/**
 * Interface for handler factory.
 */
interface HandlerFactoryInterface
{
    /**
     * Create handler.
     *
     * @return void
     */
    public function create(string $classname): HandlerInterface;
}
