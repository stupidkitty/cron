<?php
namespace SK\CronModule\Handler;

class HandlerFactory implements HandlerFactoryInterface
{
    /**
     * Create handler by class name
     *
     * @param string $classname
     * @return HandlerInterface
     */
    public function create(string $classname): HandlerInterface
    {
        return new $classname;
    }
}
