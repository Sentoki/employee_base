<h1>Список сотрудников</h1>

<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>

<?php $form = ActiveForm::begin(
        [
            'id' => 'filter-form',
            'layout' => 'horizontal',
            'method' => 'get',
        ]
); ?>

<?= $form
    ->field($filterForm, 'department')
    ->dropDownList($departments, ['prompt' => 'Укажите отдел'])->label('Отдел'); ?>
<?= $form
    ->field($filterForm, 'isWork')
    ->dropDownList([1 => 'Работает', 2 => 'Уволен'], ['prompt' => 'Все сотрудники'])->label('Статус'); ?>

<div class="form-group">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
</div>

<?php ActiveForm::end(); ?>
<table class="table table-striped">
    <tr>
        <th>Сотрудник</th>
        <th>Отдел</th>
        <th>Должность</th>
        <th>Работает ли</th>
        <th>Ссылки в админку</th>
    </tr>
<?php
/** @var \app\models\Employee $employee */
foreach ($employees as $employee) {
    $name = $employee->last_name . ' ' . $employee->first_name . ' ' . $employee->patrynomic;
    $url = \yii\helpers\Url::to(['site/employee', 'employee_id' => $employee->id]);
    echo "<tr>
<td><a href='{$url}'>{$name}</a></td>
<td>{$employee->department->title}</td>
<td>{$employee->position->title}</td>
<td>" . (is_null($employee->leave_date) ? 'Работает' : 'Уволен') . "</td>
<td>
<a href='".Url::to(['admin/employee-edit', 'employee_id' => $employee->id])."'>
<span class=\"glyphicon glyphicon-edit\"></span>
</a>
</td>
</tr>";
}

?>
</table>
<?php

echo LinkPager::widget([
    'pagination' => $pages,
]);