<?php
namespace SK\CronModule\Handler;

/**
 * Interface for handler.
 */
interface HandlerInterface
{
    /**
     * Execute handler.
     *
     * @return void
     */
    public function run();
}
