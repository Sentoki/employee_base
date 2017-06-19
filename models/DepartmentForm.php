<?php

namespace app\models;

use yii\base\Model;

/**
 * Модель формы для создания и редактирования отделов
 *
 * Class DepartmentForm
 * @package app\models
 */
class DepartmentForm extends Model
{
    public $title;
    public $description;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['title', 'string'],
            ['description', 'string'],
        ];
    }
}
