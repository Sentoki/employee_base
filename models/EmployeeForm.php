<?php

namespace app\models;

use yii\base\Model;

/**
 * Модель формы для редактирования сотрудников
 *
 * Class EmployeeForm
 * @package app\models
 */
class EmployeeForm extends Model
{
    public $first_name;
    public $last_name;
    public $patrynomic;
    public $birth_date;
    public $email;
    public $phone;
    public $employment_date;
    public $leave_date;
    public $position_id;
    public $department_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                [
                    'first_name',
                    'last_name',
                    'patrynomic',
                    'birth_date',
                    'email',
                    'phone',
                    'employment_date',
                    'position_id',
                    'department_id',
                ],
                'required'
            ],
            ['first_name', 'string'],
            ['last_name', 'string'],
            ['patrynomic', 'string'],
            ['birth_date', 'date', 'format' => 'Y-m-d'],
            ['email', 'email'],
            ['phone', 'string'],
            ['employment_date', 'date', 'format' => 'Y-m-d'],
            ['leave_date', 'date', 'format' => 'Y-m-d'],
            ['position_id', 'integer'],
            ['department_id', 'integer'],
        ];
    }
}
