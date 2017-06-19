<?php
/** @var \app\models\Employee $employee */
$employee;
?>

<h1><?= "{$employee->last_name} {$employee->first_name} {$employee->patrynomic}"; ?></h1>

<p>Отдел: <?= $employee->department->title; ?></p>
<p>Должность: <?= $employee->position->title; ?></p>
<p>Дата трудоустройства: <?= (new DateTime($employee->employment_date))->format('d-m-Y'); ?></p>
<p>Текущий статус: <?=\app\helpers\EmployeeHelper::employeeTextStatus($employee);?></p>
<p>Дата рождения: <?= (new DateTime($employee->birth_date))->format('d-m-Y'); ?></p>
<p>Email: <?= $employee->email; ?></p>
<p>Телефон: <?= $employee->phone; ?></p>