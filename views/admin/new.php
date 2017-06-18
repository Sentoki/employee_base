<h1>Новый отдел</h1>

<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin(
    [
        'id' => 'filter-form',
        'layout' => 'horizontal',
        'method' => 'post',
    ]
); ?>

<?= $form
    ->field($newDepartmentForm, 'title')
    ->label('Название отдела'); ?>
<?= $form
    ->field($newDepartmentForm, 'description')
    ->label('Описание отдела'); ?>

<div class="form-group">
    <?= Html::submitButton(
        'Сохранить',
        ['class' => 'btn btn-primary', 'name' => 'save-button']
    ) ?>
</div>

<?php ActiveForm::end(); ?>