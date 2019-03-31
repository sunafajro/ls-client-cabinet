<?php

namespace app\controllers;

use Yii;
use app\models\ChangeUsernameForm;
use app\models\ChangePasswordForm;
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
    
    public function actionProfile()
    {
        $student = Student::findOne(Yii::$app->user->id);
        $lessons = $student ? $student->getPassedLessonsByService() : [];
		$serviceIds = [];
		foreach ($lessons as $lesson) {
			$serviceIds[] = $lesson['serviceid'];
		}
        $services = $student ? $student->getOrderedLessonsByService($serviceIds) : [];
        return $this->render('profile', [
            'lessons' => $lessons,
            'services' => $services
        ]);
    }

    public function actionPayments()
    {
        $student = Student::findOne(Yii::$app->user->id);
        $payments = $student ? $student->getPayments() : [];
        return $this->render('payments', [
            'payments' => $payments
        ]);
    }

    public function actionLessons($page = 1)
    {
        $limit = 10;
        $student = Student::findOne(Yii::$app->user->id);
        $lessons = $student->getLessons();
        list($comments, $total) = $student ? $student->getLessonsComments($limit, ($page - 1) * $limit) : [[], []];
        return $this->render('lessons', [
            'lessons' => $lessons,
            'comments' => $comments,
            'currentPage' => $page,
            'totalPages' => ceil($total->totalCount / $limit)
        ]);
    }

    public function actionAttestations()
    {
        $student = Student::findOne(Yii::$app->user->id);
        $attestations = $student ? $student->getAttestations() : [];
        return $this->render('attestations', [
            'grades' => $attestations
        ]);  
    }

    public function actionMessages()
    {
        return $this->render('messages', [
            
        ]);
    }

    public function actionSettings()
    {
        $changeUsername = new ChangeUsernameForm();
        $changePassword = new ChangePasswordForm();
        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post('ChangeUsernameForm')) {
                if ($changeUsername->load(Yii::$app->request->post()) && $changeUsername->save()) {
                    $changeUsername = new ChangeUsernameForm();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Login parameters successfully updated'));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to update login parameters'));
                }
            }
            if (Yii::$app->request->post('ChangePasswordForm')) {
                if ($changePassword->load(Yii::$app->request->post()) && $changePassword->save()) {
                    $changePassword = new ChangePasswordForm();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Login parameters successfully updated'));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to update login parameters'));
                }
            }
        }
        return $this->render('settings', [
            'changeUsername' => $changeUsername,
            'changePassword' => $changePassword
        ]);
    }
}