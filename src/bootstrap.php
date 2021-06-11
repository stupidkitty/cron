<?php

namespace SK\CronModule;

use Yii;

$container = Yii::$container;

$container->setSingleton(Executor\ScheduledExecutorInterface::class, Executor\ScheduledExecutor::class);
$container->setSingleton(Scheduler\SchedulerInterface::class, Scheduler\TaskScheduler::class);
$container->setSingleton(Handler\HandlerFactoryInterface::class, Handler\HandlerFactory::class);
