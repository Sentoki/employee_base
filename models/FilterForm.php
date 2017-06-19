<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Модель для формы фильтрации сотрудников
 *
 * Class FilterForm
 * @package app\models
 */
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
