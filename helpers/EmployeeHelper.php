<?php

namespace app\helpers;

use app\models\AbcGroup;
use app\models\Employee;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * Вспомогательные методы
 *
 * Class EmployeeHelper
 * @package app\helpers
 */
class EmployeeHelper
{
    /**
     * Максимальное количество групп для алфавитного списка
     */
    const MAX_GROUP_NUMBER = 7;
    const ALPHABET = [
        'А', 'Б','В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н',
        'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь',
        'Э', 'Ю', 'Я',
    ];

    /**
     * Получение состояния текущего работника (работает или уволен, и когда)
     * для использования в представлениях
     *
     * @param Employee $employee объект работника для которого выводим статус
     * @return string текстовое представление статуса
     */
    public static function employeeTextStatus(Employee $employee) : string
    {
        if ($employee->leave_date === null) {
            return 'Работает';
        } else {
            $date = new \DateTime($employee->leave_date);
            return 'Уволен ' . $date->format('d-m-Y');
        }
    }

    public static function getLettersCount() : array
    {
        $lettersCount = \Yii::$app->db->createCommand(
            'SELECT
            substring(trim(last_name) from 1 for 1) as Letter,
            count(substring(trim(last_name) from 1 for 1)) as number
            FROM employees.employee GROUP BY Letter ORDER BY Letter ASC;'
        )->queryAll();
        return $lettersCount;
    }

    public static function getGroupsByLetterNumbers(
        array $lettersNumber,
        int $numberPerGroup
    ) : array {
        $count = 0;
        $groups = [];
        $currentGroup =[];
        $currentGroup[] = $lettersNumber[0]['letter'];
        foreach ($lettersNumber as $key => $element) {
            $count += $element['number'];
            if ($count >= $numberPerGroup) {
                $count = 0;
                $currentGroup[] = $element['letter'];
                $groups[] = $currentGroup;
                if (isset($lettersNumber[$key + 1])) {
                    $currentGroup = [];
                    $currentGroup[] = $lettersNumber[$key + 1]['letter'];
                }
            }
        }
        if (sizeof($currentGroup) != 2) {
            $currentGroup[] = $lettersNumber[sizeof($lettersNumber) - 1]['letter'];
            $groups[] = $currentGroup;
        }
        return $groups;
    }

    /**
     * Расчёт среднего количества сотрудников в группе
     *
     * @param int $employeesNumber количество сотрудников
     * @return int среднее количество сотрудников на группу
     */
    public static function getAvgEmployeesPerGroup(int $employeesNumber) : int
    {
        return ceil($employeesNumber / self::MAX_GROUP_NUMBER);
    }

    public static function updateAbcGroups()
    {
        \Yii::$app->db->createCommand('delete from employees.abc_group')->execute();
        $employeesNumber = \Yii::$app->db->createCommand(
            'select count(id) from employees.employee'
        )->queryOne();
        $perGroup = self::getAvgEmployeesPerGroup($employeesNumber['count']);
        $letterNumbers = self::getLettersCount();
        $groups = self::getGroupsByLetterNumbers($letterNumbers, $perGroup);
        foreach ($groups as $group) {
            $abcGroup = new AbcGroup();
            $abcGroup->from = $group[0];
            $abcGroup->to = $group[1];
            $abcGroup->save();
        }
    }

    public static function getAbcMenu()
    {
        $content = '';
        $groups = AbcGroup::find()->all();
        foreach ($groups as $key => $group) {
            $href = Url::to(['site/abc', 'abcGroupId' => $group->id]);
            $content .= "<a href='{$href}'>{$group->from} - {$group->to}</a>";
            if ($key < (sizeof($groups) - 1)) {
                $content .= ' | ';
            }
        }
        return $content;
    }

    public static function getEmployeesQueryByGroup(AbcGroup $abcGroup) : ActiveQuery
    {
        $begin = array_search($abcGroup->from, self::ALPHABET);
        $end = array_search($abcGroup->to, self::ALPHABET);
        $letters = array_slice(self::ALPHABET, $begin, ($end - $begin + 1));
        $query = Employee::find()
            ->joinWith(['department', 'position'])
            ->with(['department', 'position']);
        foreach ($letters as $letter) {
            $query->orWhere(['last_name_letter' => $letter]);
        }
        return $query;
    }
}
