<h1>Employee list</h1>

<?php

use yii\widgets\LinkPager;

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
    $name = $employee->lastname . ' ' . $employee->firstname;
    echo "<tr>
<td>{$name}</td>
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