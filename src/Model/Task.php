<?php
namespace SK\CronModule\Model;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "cron_tasks".
 *
 * @property integer $task_id
 * @property string $expression
 * @property string $handler
 * @property integer $priority
 * @property string $last_execution
 * @property double $duration
 * @property integer $status
 * @property boolean $enabled
 * @property timestamp $created_at
 */
class Task extends ActiveRecord implements TaskInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cron_tasks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expression', 'handler'], 'required'],
            [['priority'], 'integer'],
            [['enabled'], 'boolean'],
            [['duration'], 'double'],
            [['handler', 'status'], 'string'],
            [['expression'], 'string', 'max' => 24],
            [['created_at', 'last_execution'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['created_at'], 'default', 'value' => \gmdate('Y-m-d H:i:s')],
            ['priority', 'default', 'value' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->task_id;
    }

    /**
     * @inheritdoc
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @inheritdoc
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
    }

    /**
     * @inheritdoc
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @inheritdoc
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @inheritdoc
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @inheritdoc
     */
    public function getLastExecution()
    {
        return $this->last_execution;
    }

    /**
     * @inheritdoc
     */
    public function setLastExecution($last_execution)
    {
        $this->last_execution = $last_execution;
    }

    /**
     * @inheritdoc
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @inheritdoc
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * Проверяет активна таска или нет.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Устанавливает флаг активности таски.
     *
     * @param boolean|null $enabled
     * @return void
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;
    }

    /**
     * Включает таску.
     *
     * @return void
     */
    public function enable()
    {
        $this->enabled = true;
    }

    /**
     * Выключает таску
     *
     * @return void
     */
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * Возвращает время создания таски.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
}
