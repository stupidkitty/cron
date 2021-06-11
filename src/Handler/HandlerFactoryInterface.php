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
     * @param string $classname
     * @return HandlerInterface
     */
    public function create(string $classname): HandlerInterface;
}
