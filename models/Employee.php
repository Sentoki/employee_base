<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employees.employee".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $last_name_letter
 * @property string $birth_date
 * @property string $email
 * @property string $phone
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
            [['first_name', 'last_name', 'last_name_letter', 'birth_date', 'email', 'phone', 'employment_date', 'position_id', 'department_id'], 'required'],
            [['birth_date', 'employment_date', 'leave_date', 'created_at', 'updated_at'], 'safe'],
            [['position_id', 'department_id'], 'integer'],
            [['first_name', 'last_name', 'email', 'phone'], 'string', 'max' => 255],
            [['last_name_letter'], 'string', 'max' => 1],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'last_name_letter' => 'Last Name Letter',
            'birth_date' => 'Birth Date',
            'email' => 'Email',
            'phone' => 'Phone',
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
