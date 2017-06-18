<?php

namespace app\models;

use Yii;
use yii\base\Model;

class FilterForm extends Model
{
    public $department;
    public $isWork;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['department', 'integer'],
            ['isWork', 'integer'],
        ];
    }
}
