<h1>Редактирование</h1>

<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

if (Yii::$app->session->hasFlash('success')) {
    ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success'); ?>
    </div>
    <?php
}

$form = ActiveForm::begin(
    [
        'id' => 'filter-form',
        'layout' => 'horizontal',
        'method' => 'post',
    ]
); ?>

<?= $form
    ->field($editForm, 'title')
    ->label('Название отдела'); ?>
<?= $form
    ->field($editForm, 'description')
    ->label('Описание отдела'); ?>

<div class="form-group">
    <?= Html::submitButton(
            'Сохранить',
            ['class' => 'btn btn-primary', 'name' => 'save-button']
    ) ?>
</div>

<?php ActiveForm::end(); ?>