<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employees.position".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property EmployeesEmployee[] $employeesEmployees
 */
class Position extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employees.position';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeesEmployees()
    {
        return $this->hasMany(EmployeesEmployee::className(), ['position_id' => 'id']);
    }
}
