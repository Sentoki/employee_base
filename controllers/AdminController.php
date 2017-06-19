<?php

namespace app\controllers;

use app\models\Department;
use app\models\DepartmentForm;
use app\models\Employee;
use app\models\EmployeeForm;
use app\models\Position;
use Yii;
use yii\db\IntegrityException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class AdminController extends \yii\web\Controller
{
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
        $employee = Employee::findOne(['id' => $employee_id]);
        $form = new EmployeeForm();
        $form->load($employee->attributes, '');
        $positions = Position::find()->all();
        $positions = ArrayHelper::map($positions, 'id', 'title');
        $departments = Department::find()->all();
        $departments = ArrayHelper::map($departments, 'id', 'title');

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $employee->attributes = $form->getAttributes();
            $employee->save();
            Yii::$app->session->setFlash(
                'success',
                'Данные сотрудника обновлены'
            );
        }

        return $this->render(
            'employee_edit',
            [
                'editForm' => $form,
                'positions' => $positions,
                'departments' => $departments,
                'errors' => $form->getErrors(),
                'employee' => $employee,
            ]
        );
    }

    public function actionEmployeeFire($employee_id)
    {
        $employee = Employee::findOne(['id' => $employee_id]);
        $employee->leave_date = (new \DateTime('now'))->format('Y-m-d');
        $employee->save();
        Yii::$app->session->setFlash(
            'warning',
            'Сотрудник уволен'
        );
        $this->redirect(
            Url::to(['admin/employee-edit', 'employee_id' => $employee->id])
        );
    }
}
