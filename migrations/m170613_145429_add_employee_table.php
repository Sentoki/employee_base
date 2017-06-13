<?php

use yii\db\Migration;

class m170613_145429_add_employee_table extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE SCHEMA employees;');
        $this->createTable(
            'employees.employee',
            [
                'id' => $this->primaryKey(),
                'first_name' => $this->string()->notNull(),
                'last_name' => $this->string()->notNull(),
                'birth_date' => $this->date()->notNull(),
                'email' => $this->string()->notNull(),
                'employment_date' => $this->date()->notNull(),
                'leave_date' => $this->date(),
                'position_id' => $this->integer()->notNull(),
                'department_id' => $this->integer()->notNull(),
                'created_at' => $this->dateTime()->defaultValue('now'),
                'updated_at' => $this->dateTime(),
            ]
        );
        $this->execute('CREATE OR REPLACE FUNCTION updated_at()	
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = now();
    RETURN NEW;	
END;
$$ language \'plpgsql\';');
        $this->execute(
            'CREATE TRIGGER updated_at BEFORE UPDATE ON employees.employee FOR EACH ROW EXECUTE PROCEDURE updated_at();'
        );
    }

    public function safeDown()
    {
        $this->dropTable('employees.employee');
        $this->execute('drop FUNCTION updated_at();');
        $this->execute('DROP SCHEMA employees;');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170613_145429_add_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
