<?php
declare(strict_types=1);

namespace app\tests;

use app\helpers\EmployeeHelper;
use app\models\Employee;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    /**
     * @test
     */
    public function employeeStatus()
    {
        $employee = new Employee();
        $employee->leave_date = null;
        $leave_date = null;
        $status = EmployeeHelper::employeeTextStatus($employee);
        $this->assertEquals('Работает', $status);

        $employee = new Employee();
        $employee->leave_date = '2013-10-10';
        $status = EmployeeHelper::employeeTextStatus($employee);
        $this->assertEquals('Уволен 10-10-2013', $status);
    }

    /**
     * @test
     */
    public function getGroupsByLetterNumbers()
    {
        $letters = [
            [
                'letter' => 'А',
                'number' => 30,
            ],
            [
                'letter' => 'Б',
                'number' => 30,
            ],
            [
                'letter' => 'В',
                'number' => 30,
            ],
            [
                'letter' => 'Г',
                'number' => 30,
            ],
            [
                'letter' => 'Д',
                'number' => 30,
            ],
            [
                'letter' => 'Е',
                'number' => 30,
            ],
            [
                'letter' => 'Ё',
                'number' => 30,
            ],
            [
                'letter' => 'Ж',
                'number' => 30,
            ],
            [
                'letter' => 'З',
                'number' => 30,
            ],
            [
                'letter' => 'И',
                'number' => 30,
            ],
        ];
        $groups = EmployeeHelper::getGroupsByLetterNumbers($letters, 100);
        $expected = [
            ['А', 'Г'],
            ['Д', 'Ж'],
            ['З', 'И'],
        ];
        $this->assertEquals($expected, $groups);
    }

    /**
     * @test
     */
    public function getAvgEmployeesPerGroup()
    {
        $number = EmployeeHelper::getAvgEmployeesPerGroup(300);
        $this->assertEquals(43, $number);

        $number = EmployeeHelper::getAvgEmployeesPerGroup(350);
        $this->assertEquals(50, $number);

        $number = EmployeeHelper::getAvgEmployeesPerGroup(556);
        $this->assertEquals(80, $number);

        $number = EmployeeHelper::getAvgEmployeesPerGroup(800);
        $this->assertEquals(115, $number);

    }
}
