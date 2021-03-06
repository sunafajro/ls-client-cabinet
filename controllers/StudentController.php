<?php

namespace app\controllers;

use app\models\File;
use Yii;
use app\models\ChangeUsernameForm;
use app\models\ChangePasswordForm;
use app\models\Message;
use app\models\Student;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use kartik\mpdf\Pdf;

class StudentController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['profile', 'payments', 'lessons', 'attestations', 'download-attestation', 'messages', 'settings'],
                'rules' => [
                    [
                        'actions' => ['profile', 'payments', 'lessons', 'attestations', 'download-attestation', 'messages', 'settings'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['profile', 'payments', 'lessons', 'attestations', 'download-attestation', 'messages', 'settings'],
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
        if ($student) {
            $lessons = $student->getPassedLessonsByService();
            $serviceIds = [];
            foreach ($lessons as $lesson) {
                $serviceIds[] = $lesson['serviceId'];
            }
            $services = $student->getOrderedLessonsByService($serviceIds);
            $balance = $student->calculateBalance(
                $student->debt,
                $services,
                $lessons
            );
            $schedule = $student->getSchedule();
            $teachers = $student->getTeachers();
            return $this->render('profile', [
                'balance'  => $balance,
                'lessons'  => $lessons,
                'schedule' => $schedule,
                'services' => $services,
                'student'  => $student,
                'teachers' => $teachers,
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionPayments()
    {
        $student = Student::findOne(Yii::$app->user->id);
        if ($student) {
            $payments = $student->getPayments();
            return $this->render('payments', [
                'payments' => $payments,
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionLessons($page = 1)
    {
        $student = Student::findOne(Yii::$app->user->id);
        if ($student) {
            $limit = 10;
            $lessons = $student->getLessons();
            list($comments, $total) = $student->getLessonsComments($limit, ($page - 1) * $limit);
            return $this->render('lessons', [
                'lessons'     => $lessons,
                'comments'    => $comments,
                'currentPage' => $page,
                'totalPages'  => ceil($total->totalCount / $limit),
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionAttestations()
    {
        $student = Student::findOne(Yii::$app->user->id);
        if ($student) {
            $attestations = $student->getAttestations();
            return $this->render('attestations', [
                'contentTypes' => Student::getExamContentTypes(),
                'grades'       => $attestations,
                'exams'        => Student::getExams(),
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionMessages()
    {
        $student = Student::findOne(Yii::$app->user->id);
        if ($student) {
            $messageForm = new Message();
            $messages = $student->getMessages();
            $receivers = $student->availableMessageReceiversList();
            if (Yii::$app->request->isPost && $messageForm->load(Yii::$app->request->post())) {
                $files = [];
                if (is_array($messageForm->files) && !empty($messageForm->files)) {
                    $files = $messageForm->files;
                    $messageForm->files = '';
                }
                if ($messageForm->save()) {
                    foreach ($files ?? [] as $fileId) {
                        $file = File::find()->andWhere(['id' => $fileId])->one();
                        $file->setEntity(File::TYPE_ATTACHMENTS, $messageForm->id);
                    }
                    $messageReport = (new \yii\db\Query())
                    ->createCommand()
                    ->insert(
                        'calc_messreport',
                        [
                            'calc_message' => $messageForm->id,
                            'user'         => $messageForm->refinement_id,
                            'ok'           => 0,
                            'data'         => date('Y-m-d H:i:s'),
                            'send'         => 1
                        ]
                    )->execute();;
                    $messageForm = new Message();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Message successfully sended'));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to send message'));
                }
            }
            return $this->render('messages', [
                'messages'    => $messages,
                'messageForm' => $messageForm,
                'receivers'   => $receivers,
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
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
            'changePassword' => $changePassword,
        ]);
    }

    public function actionDownloadAttestation($id)
    {
        $student = Student::findOne(Yii::$app->user->id);
        if ($student) {
            $attestation = $student->getAttestation($id);
            if ($attestation) {                
                $filePath = Yii::getAlias("@attestates/{$student->id}/attestate-{$id}.pdf");
                if (!file_exists($filePath)) {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
                return Yii::$app->response->sendFile($filePath);
            } else {
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}