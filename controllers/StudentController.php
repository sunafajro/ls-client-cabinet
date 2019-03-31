<?php

namespace app\controllers;

use Yii;
use app\models\Student;
use yii\filters\AccessControl;
use yii\web\Controller;

class StudentController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['profile', 'payments', 'lessons', 'attestations', 'messages', 'settings'],
                'rules' => [
                    [
                        'actions' => ['profile', 'payments', 'lessons', 'attestations', 'messages', 'settings'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['profile', 'payments', 'lessons', 'attestations', 'messages', 'settings'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionPayments()
    {
        $student = Student::findOne(Yii::$app->user->id);
        $payments = $student ? $student->getPayments() : [];
        return $this->render('payments', [
            'payments' => $payments
        ]);
    }
}