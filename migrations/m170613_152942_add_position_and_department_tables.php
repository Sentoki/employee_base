<?php

use yii\db\Migration;

class m170613_152942_add_position_and_department_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            'employees.position',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull()->unique(),
                'description' => $this->string()->notNull(),
                'created_at' => $this->dateTime()->defaultValue('now'),
                'updated_at' => $this->dateTime(),
            ]
        );
        $this->execute(
            'CREATE TRIGGER updated_at BEFORE UPDATE ON employees.position FOR EACH ROW EXECUTE PROCEDURE updated_at();'
        );
        $this->createTable(
            'employees.department',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull()->unique(),
                'description' => $this->string()->notNull(),
                'created_at' => $this->dateTime()->defaultValue('now'),
                'updated_at' => $this->dateTime(),
            ]
        );
        $this->execute(
            'CREATE TRIGGER updated_at BEFORE UPDATE ON employees.department FOR EACH ROW EXECUTE PROCEDURE updated_at();'
        );
        $this->addForeignKey(
            'employee_to_position_fk',
            'employees.employee',
            'position_id',
            'employees.position',
            'id'
        );
        $this->addForeignKey(
            'employee_to_department_fk',
            'employees.employee',
            'department_id',
            'employees.department',
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('employee_to_position_fk', 'employees.employee');
        $this->dropForeignKey('employee_to_department_fk', 'employees.employee');
        $this->dropTable('employees.position');
        $this->dropTable('employees.department');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170613_152942_add_position_and_department_tables cannot be reverted.\n";

        return false;
    }
    */
}
