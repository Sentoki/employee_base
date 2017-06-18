<h1>Отделы</h1>

<?php
use yii\helpers\Url;

if (Yii::$app->session->hasFlash('error')) {
    ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('error'); ?>
    </div>
    <?php
}
if (Yii::$app->session->hasFlash('success')) {
    ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success'); ?>
    </div>
    <?php
}
?>
<a class="btn btn-primary" style="margin-bottom: 5px;" href="<?= Url::to(['new']) ?>">Добавить отдел</a>

<table class="table table-striped">
    <tr>
        <th>Название отдела</th>
        <th>Описание</th>
        <th>Последнее редактирование</th>
        <th>Действия</th>
    </tr>
<?php
/** @var \app\models\Department $department */

foreach ($departments as $department) {
    if (!is_null($department->updated_at)) {
        $lastEdit = new DateTime($department->updated_at);
    } else {
        $lastEdit = new DateTime($department->created_at);
    }
    $lastEdit = $lastEdit->format('d-m-Y H:m:i');
    $editUrl = Url::to(['admin/edit', ['department_id' => '']]);
    $deleteUrl = '';
    $addNewUrl = '';
    echo "<tr>
<td>{$department->title}</td>
<td>{$department->description}</td>
<td>{$lastEdit}</td>
<td>
<a href='".Url::to(['admin/department-edit', 'department_id' => $department->id])."'>
<span class=\"glyphicon glyphicon-edit\"></span>
</a>
<a href='".Url::to(['admin/department-delete', 'department_id' => $department->id])."'><span class=\"glyphicon glyphicon-trash\"></span></a>
</td>
</tr>";
}
?>
</table>
