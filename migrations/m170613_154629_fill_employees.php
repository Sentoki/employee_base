<?php

use yii\db\Migration;

class m170613_154629_fill_employees extends Migration
{
    public function safeUp()
    {
        $this->batchInsert(
            'employees.department',
            ['id', 'title', 'description'],
            [
                [1, 'Грузчики', 'Переносят тяжести с места на место'],
                [2, 'Кладовщики', 'Учёт тяжестей'],
                [3, 'Бухгалтерия', 'Учёт денег'],
                [4, 'Слесари', 'Те кто работают за станком'],
                [5, 'Повары', 'Всех кормят'],
                [6, 'Эникейщик', 'Нажимает кнопки'],
                [7, 'Водитель', 'Перемещает если далеко'],
                [8, 'Уборщики', 'Следят ха порядком'],
            ]
        );
        $this->batchInsert(
            'employees.position',
            ['id', 'title', 'description'],
            [
                [1, 'Руководитель', 'Указывает что и кому делать'],
                [2, 'Заместитель руководителя', 'Помогает начальнику'],
                [3, 'Секретарь руководителя', 'Бумажная работа'],
                [4, 'Рабочий', 'Делает работу'],
                [5, 'Стажёр', 'Учится делать работу'],
            ]
        );
        $this->batchInsert(
            'employees.employee',
            [
                'firstname',
                'lastname',
                'birth_date',
                'email',
                'employment_date',
                'leave_date',
                'position_id',
                'department_id',
            ],
            [
                ['Руководитель', 'Указывает что и кому делать'],
                ['Заместитель руководителя', 'Помогает начальнику'],
                ['Секретарь руководителя', 'Бумажная работа'],
                ['Рабочий', 'Делает работу'],
                ['Стажёр', 'Учится делать работу'],
            ]
        );
    }

    public function safeDown()
    {
        $this->delete('employees.department');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170613_154629_fill_employees cannot be reverted.\n";

        return false;
    }
    */
}
