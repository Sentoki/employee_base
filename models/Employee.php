<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employees.employee".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $birth_date
 * @property string $email
 * @property string $employment_date
 * @property string $leave_date
 * @property integer $position_id
 * @property integer $department_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Department $department
 * @property Position $position
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employees.employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'birth_date', 'email', 'employment_date', 'position_id', 'department_id'], 'required'],
            [['birth_date', 'employment_date', 'leave_date', 'created_at', 'updated_at'], 'safe'],
            [['position_id', 'department_id'], 'integer'],
            [['firstname', 'lastname', 'email'], 'string', 'max' => 255],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeesDepartment::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeesPosition::className(), 'targetAttribute' => ['position_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'birth_date' => 'Birth Date',
            'email' => 'Email',
            'employment_date' => 'Employment Date',
            'leave_date' => 'Leave Date',
            'position_id' => 'Position ID',
            'department_id' => 'Department ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['id' => 'department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Position::className(), ['id' => 'position_id']);
    }
}
