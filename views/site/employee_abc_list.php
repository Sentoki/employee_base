<h1>Алфавитный указатель сотрудников</h1>

<?php

use yii\widgets\LinkPager;

echo \app\helpers\EmployeeHelper::getAbcMenu();

if (!isset($abcGroupId)) {
    echo "<h2>Необходимо выбрать группу сотрудников</h2>";
}
/** @var \yii\data\Pagination $pages */
echo "<h3>В данном разделе {$pages->totalCount} сотрудников</h3>";

?>
<table class="table table-striped">
    <tr>
        <th>Сотрудник</th>
        <th>Отдел</th>
        <th>Должность</th>
        <th>Работает ли</th>
    </tr>
<?php
/** @var \app\models\Employee $employee */
foreach ($employees as $employee) {
    $name = $employee->last_name . ' ' . $employee->first_name;
    $url = \yii\helpers\Url::to(['site/employee', 'employee_id' => $employee->id]);
    echo "<tr>
<td><a href='{$url}'>{$name}</a></td>
<td>{$employee->department->title}</td>
<td>{$employee->position->title}</td>
<td>" . (is_null($employee->leave_date) ? 'Работает' : 'Уволен') . "</td>
</tr>";
}

?>
</table>
<?php

echo LinkPager::widget([
    'pagination' => $pages,
]);