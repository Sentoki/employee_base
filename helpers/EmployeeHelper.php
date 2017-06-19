<?php

namespace app\helpers;

use app\models\AbcGroup;
use app\models\Employee;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * Вспомогательные методы для работы с данными. По мере роста функционала можно
 * выносить в отдельные классы и основываясь на требуемых объектах делать методы
 * не статическими.
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
    /**
     * Алфавит
     */
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

    /**
     * Получаем массив с данными о том сколько фамилий ни каждую букву
     *
     * @return array
     */
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

    /**
     * Формирует массив который делит алфавит на группы
     *
     * @param array $lettersNumber массив букв и количества фамилий на эту букву
     * @param int $numberPerGroup приблизительно допустимое количество сотрудников
     * в группе
     *
     * @return array массив с сформированными группами
     */
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
            $nextProbCount = self::getNextProbCount($lettersNumber, $count, $key);
            if ($nextProbCount >= $numberPerGroup * 1.1) {
                $count = 0;
                $currentGroup[] = $element['letter'];
                $groups[] = $currentGroup;
                if (isset($lettersNumber[$key + 1])) {
                    $currentGroup = [];
                    $currentGroup[] = $lettersNumber[$key + 1]['letter'];
                }
            } else {
            }
        }
        if (sizeof($currentGroup) != 2) {
            $currentGroup[] = $lettersNumber[sizeof($lettersNumber) - 1]['letter'];
            $groups[] = $currentGroup;
        }
        return $groups;
    }

    /**
     * Рассчитывает сколько будет сотрудников в группе если включить следующую
     * букву алфавита
     *
     * @param array $lettersNumber массив букв с количеством сотрудников на эту букву
     * @param int $currentCount количество сотрудников в группе в текущем виде
     * @param int $currentKey индекс буквы из массива
     *
     * @return int ожидаемое количество сотрудников в группе
     */
    public static function getNextProbCount(
        array $lettersNumber,
        int $currentCount,
        int $currentKey
    ) : int {
        if (isset($lettersNumber[$currentKey+1])) {
            return $currentCount + $lettersNumber[$currentKey+1]['number'];
        } else {
            return $currentCount;
        }
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

    /**
     * Обновление групп для алфавитного отображения сотрудников
     */
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

    /**
     * Получение html-кода для меню алфавитного списка
     *
     * @param int|null $abcGroupId идентификатор текущей группы
     * @return string html код меню
     */
    public static function getAbcMenu(int $abcGroupId = null) : string
    {
        $content = '';
        $groups = AbcGroup::find()->all();
        foreach ($groups as $key => $group) {
            $href = Url::to(['site/abc', 'abcGroupId' => $group->id]);
            if (isset($abcGroupId) && $abcGroupId == $group->id) {
                $style = "style='color: black; text-decoration: underline;'";
            } else {
                $style = '';
            }
            $content .= "<a {$style} href='{$href}'>{$group->from} - {$group->to}</a>";
            if ($key < (sizeof($groups) - 1)) {
                $content .= ' | ';
            }
        }
        return $content;
    }

    /**
     * Формирует объект запроса для получения сотрудников и пагинации в алфавитном
     * отображении
     *
     * @param AbcGroup $abcGroup группа для которой требуется получить сотрудников
     *
     * @return ActiveQuery объект запроса
     */
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
