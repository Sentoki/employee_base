<?php

namespace app\controllers;

use app\helpers\EmployeeHelper;
use app\models\AbcGroup;
use app\models\Department;
use app\models\Employee;
use app\models\FilterForm;
use app\models\Position;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
    }

    public function actionEmployee($employee_id)
    {
        $employee = Employee::find()
            ->joinWith(['department', 'position'])
            ->with(['department', 'position'])
            ->limit(1)
            ->where(['employee.id' => $employee_id])
            ->one();
        return $this->render('employee', ['employee' => $employee]);

    }

    public function actionEmployeeList()
    {
        $filterForm = new FilterForm();
        $query = Employee::find()
            ->joinWith(['department', 'position']);
        $employees = $query->with(['department', 'position']);
        if ($filterForm->load(Yii::$app->request->get()) && $filterForm->validate()) {
            if (isset($filterForm->department) && $filterForm->department != '') {
                $employees->where(['department_id' => $filterForm->department]);
            }
            if (isset($filterForm->isWork) && (integer)$filterForm->isWork === 2) {
                $employees->andWhere(['NOT', ['leave_date' => null]]);
            } elseif (isset($filterForm->isWork) && (integer)$filterForm->isWork === 1) {
                $employees->andWhere(['is', 'leave_date', null]);
            }
        }
        $pages = new Pagination([
            'totalCount' => (clone $employees)->count(),
            'defaultPageSize' => 30,
        ]);
        $employees = $employees
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $departments = Department::find()->all();
        $departments = ArrayHelper::map($departments, 'id', 'title');

        return $this->render(
            'employee_list',
            [
                'employees' => $employees,
                'departments' => $departments,
                'pages' => $pages,
                'filterForm' => $filterForm,
            ]
        );
    }

    public function actionAbc($abcGroupId = null)
    {
        if (isset($abcGroupId)) {
            $group = AbcGroup::findOne(['id' => $abcGroupId]);
            $query = EmployeeHelper::getEmployeesQueryByGroup($group);
            $pages = new Pagination([
                'totalCount' => (clone $query)->count(),
                'defaultPageSize' => 30,
            ]);
            $employees = $query
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        } else {
            $employees = [];
            $pages = new Pagination();
        }

        return $this->render(
            'employee_abc_list',
            [
                'abcGroupId' => $abcGroupId,
                'employees' => $employees,
                'pages' => $pages,
            ]
        );
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
