<?php

use yii\db\Migration;

/**
 * Class m190809_095311_create_cron
 */
class m190809_095311_create_cron extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('cron_tasks', [
            'task_id' => 'smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'expression' => 'varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'handler' => 'text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL',
            'priority' => 'smallint(5) UNSIGNED NOT NULL DEFAULT 1000',
            'last_execution' => 'timestamp NULL DEFAULT NULL',
            'duration' => 'double UNSIGNED DEFAULT 0',
            'status' => 'enum(\'\',\'planned\',\'running\',\'completed\',\'failed\',\'aborted\') COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'enabled' => 'tinyint(3) UNSIGNED NOT NULL DEFAULT 1',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181112_115700_init_cron cannot be reverted.\n";

        return false;
    }
}
