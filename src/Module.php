<?php

namespace SK\CronModule;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module as BaseModule;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;

/**
 * This is the main module class of the video extension.
 */
class Module extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'SK\CronModule\Controller';

    /**
     * @inheritdoc
     */
    public $defaultRoute = 'main/index';

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $this->setViewPath(__DIR__ . '/Resources/views');

        require(__DIR__ . '/bootstrap.php');

        parent::__construct($id, $parent, $config);
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (Yii::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'SK\CronModule\Command';
            $this->defaultRoute = 'run/index';
        }

        // translations
        if (Yii::$app->has('i18n') && empty(Yii::$app->get('i18n')->translations['cron'])) {
            Yii::$app->get('i18n')->translations['cron'] = [
                'class' => PhpMessageSource::class,
                'basePath' => __DIR__ . '/Resources/i18n',
                'sourceLanguage' => 'en-US',
            ];
        }
    }
}
