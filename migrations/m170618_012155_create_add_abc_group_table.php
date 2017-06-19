<?php

use yii\db\Migration;

class m170618_012155_create_add_abc_group_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('employees.abc_group', [
            'id' => $this->primaryKey(),
            'from' => $this->string(1)->notNull(),
            'to' => $this->string(1)->notNull(),
            'created_at' => $this->dateTime()->defaultValue('now'),
            'updated_at' => $this->dateTime(),
        ]);
        $this->execute(
            'CREATE TRIGGER updated_at BEFORE UPDATE ON employees.abc_group FOR EACH ROW EXECUTE PROCEDURE updated_at();'
        );
        \app\helpers\EmployeeHelper::updateAbcGroups();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('employees.abc_group');
    }
}
