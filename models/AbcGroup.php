<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employees.abc_group".
 *
 * @property integer $id
 * @property string $from
 * @property string $to
 * @property string $created_at
 * @property string $updated_at
 */
class AbcGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employees.abc_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['from', 'to'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
