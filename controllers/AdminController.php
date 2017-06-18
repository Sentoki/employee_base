<?php

namespace app\controllers;

use app\models\Department;
use app\models\DepartmentForm;
use Yii;
use yii\db\IntegrityException;

class AdminController extends \yii\web\Controller
{
    public $layout = 'admin';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDepartments()
    {
        $departments = Department::find()->all();
        return $this->render(
            'departments',
            [
                'departments' => $departments,
            ]
        );
    }

    public function actionDepartmentEdit($department_id)
    {
        $department = Department::findOne(['id' => $department_id]);
        $editForm = new DepartmentForm();
        if ($editForm->load(Yii::$app->request->post()) && $editForm->validate()) {
            $department->attributes = $editForm->getAttributes();
            $department->save();
            \Yii::$app
                ->getSession()
                ->setFlash('success', 'Данные сохранены');
        }
        $editForm->load($department->attributes, '');
        return $this->render(
            'department_edit',
            [
                'department' => $department,
                'editForm' => $editForm,
            ]
        );
    }

    public function actionDepartmentDelete($department_id)
    {
        $department = Department::findOne(['id' => $department_id]);
        try {
            $department->delete();
            Yii::$app->session->setFlash(
                'success',
                'Отдел успешно удалён'
            );
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash(
                'error',
                'Невозможно удалить, в отделе есть сотрудники'
            );
        }
        $this->redirect('departments');
    }

    public function actionNew()
    {
        $form = new DepartmentForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $department = new Department();
            $department->attributes = $form->attributes;
            try {
                $department->save();
                Yii::$app->session->setFlash(
                    'success',
                    'Отдел успешно создан'
                );
            } catch (IntegrityException $e) {
                Yii::$app->session->setFlash(
                    'error',
                    'Невозможно создать отдел'
                );
            }
            $this->redirect('departments');
        }

        return $this->render(
            'new',
            [
                'newDepartmentForm' => $form
            ]
        );
    }

    public function actionEmployeeEdit($employee_id)
    {
        return $this->render('employee_edit');
    }

}
