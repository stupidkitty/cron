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
 * @property string $created_at
 */
class Task extends ActiveRecord implements TaskInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%cron_tasks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
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

    public function getId(): ?int
    {
        return $this->task_id;
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function setExpression(string $expression)
    {
        $this->expression = $expression;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function setHandler(string $handler)
    {
        $this->handler = $handler;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority)
    {
        $this->priority = $priority;
    }

    public function getLastExecution(): string
    {
        return $this->last_execution;
    }

    public function setLastExecution(string $lastExecutionTime)
    {
        $this->last_execution = $lastExecutionTime;
    }

    public function getDuration(): float
    {
        return $this->duration;
    }

    public function setDuration(float $duration)
    {
        $this->duration = $duration;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * Проверяет активна таска или нет.
     *
     * @return boolean
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Устанавливает флаг активности таски.
     *
     * @param boolean|null $enabled
     * @return void
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
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

    function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at)
    {
        $this->created_at = $created_at;
    }
}
