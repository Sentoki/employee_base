<h1>Редактирование сотрудника</h1>

<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

if (Yii::$app->session->hasFlash('success')) {
    ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success'); ?>
    </div>
    <?php
}
if (Yii::$app->session->hasFlash('warning')) {
    ?>
    <div class="alert alert-warning">
        <?= Yii::$app->session->getFlash('warning'); ?>
    </div>
    <?php
}

$form = ActiveForm::begin(
    [
        'id' => 'filter-form',
        'layout' => 'horizontal',
        'method' => 'post',
    ]
);
echo $form
    ->field($editForm, 'first_name')
    ->label('Имя');
echo $form
    ->field($editForm, 'last_name')
    ->label('Фамилия');
echo $form
    ->field($editForm, 'patrynomic')
    ->label('Отчество');
echo $form
    ->field($editForm, 'birth_date')
    ->label('Дата рождения (год-месяц-день)');
echo $form
    ->field($editForm, 'email')
    ->label('Email');
echo $form
    ->field($editForm, 'phone')
    ->label('Телефон');
echo $form
    ->field($editForm, 'employment_date')
    ->label('Дата трудоустройства (год-месяц-день)');
echo $form
    ->field($editForm, 'leave_date')
    ->label('Дата увольнения (год-месяц-день)');
echo $form
    ->field($editForm, 'position_id')->dropDownList($positions)
    ->label('Должность');
echo $form
    ->field($editForm, 'department_id')->dropDownList($departments)
    ->label('Отдел');


?>

<div class="form-group">
    <?php echo Html::submitButton(
        'Сохранить',
        ['class' => 'btn btn-primary', 'name' => 'save-button']
    );
    if (is_null($employee->leave_date)) {
        $url = Url::to(['admin/employee-fire', 'employee_id' => $employee->id]);
        echo " <a href=\"{$url}\" class=\"btn btn-danger\">
        Уволить
    </a>";
    }
    ?>
</div>

<?php ActiveForm::end(); ?>