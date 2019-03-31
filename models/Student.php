<?php
namespace app\models;

use Yii;

use yii\data\Pagination;

/**
 * This is the model class for table "calc_studname".
 *
 * @property integer $id
 * @property string $name
 * @property string $fname
 * @property string $lname
 * @property string $mname
 * @property string $email
 * @property integer $visible
 * @property integer $history
 * @property string $phone
 * @property string $address 
 * @property float debt
 * @property float debt2
 * @property float invoice
 * @property float money
 * @property integer calc_sex
 * @property integer calc_cumulativediscount
 * @property integer active
 * @property integer calc_way
 * @property string description
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calc_studname';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'visible', 'history', 'calc_sex','active'], 'required'],
            [['name', 'fname', 'lname', 'mname', 'email', 'phone', 'address', 'description'], 'string'],
            [['visible', 'history', 'calc_sex', 'calc_cumulativediscount', 'active', 'calc_way'], 'integer'],
            [['debt', 'debt2', 'invoice', 'money'], 'number']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Full name'),
            'fname' => Yii::t('app', 'First name'),
            'lname' => Yii::t('app', 'Last name'),
            'mname' => Yii::t('app', 'Middle name'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
            'visible' => Yii::t('app', 'Visible'),
            'history' => Yii::t('app', 'History'),
            'phone' => Yii::t('app', 'Phone'),
            'debt' => Yii::t('app', 'Debt'),
            'debt2' => Yii::t('app', 'Debt'),
            'invoice' => Yii::t('app','Summary invoices'),
            'money' => Yii::t('app', 'Summary payments'),
            'calc_sex' => Yii::t('app', 'Sex'),
            'calc_cumulativediscount' => Yii::t('app','Cumulative discount'),
            'active' => Yii::t('app','Active'),
            'calc_way' => Yii::t('app','Way to Attract'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    public function findByIdOrUsername(string $id = null, string $username = null)
    {
        if (!$id && !$username) {
            return null;
        }

        $student = (new \yii\db\Query())
        ->select([
            'id' => 's.id',
            'active' => 's.active',
            'name' => 's.name',
            'username' => 'c.username',
            'password' => 'c.password',
            'date' => 'c.date',
        ])
        ->from(['s' => static::tableName()])
        ->innerJoin(['c' => 'tbl_client_access'], 'c.calc_studname = s.id')
        ->where([
            's.visible' => 1,
            'c.site' => 1,
        ])
        ->andFilterWhere(['c.id' => $id])
        ->andFilterWhere(['c.username' => $username])
        ->one();

        return $student ? [
            'id' => $student['id'],
            'name' => $student['name'],
            'username' => $student['username'],
            'password' => $student['password'],
            'authKey' => '',
            'accessToken' => '',
            'isActive' => $student['active'],
            'lastLoginDate' => $student['date']
        ] : null;
    }

    public function getLessonsComments($limit = 10, $offset = 0)
    {
        $comments = (new \yii\db\Query())
		->select([
            'id' => 'jg.id',
            'date' => 'jg.data',
            'comments' => 'sjg.comments'
        ])
		->from(['sjg' => 'calc_studjournalgroup'])
		->innerJoin(['jg' => 'calc_journalgroup'], 'jg.id = sjg.calc_journalgroup')
		->where([
            'sjg.calc_studname' => $this->id,
            'jg.visible' => 1
        ])
        ->andWhere(['!=', 'sjg.comments', '']);

        $countQuery = clone $comments;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);

		$comments = $comments->orderBy(['jg.data' => SORT_DESC])
        ->limit($limit)
        ->offset($offset)
        ->all();
        return [
            $comments,
            $pages,
        ];
    }

    public function getPayments()
    {
        $payments = (new \yii\db\Query())
		->select([
            'date' => 'ms.data',
            'value' => 'ms.value',
            'office' => 'o.name',
            'employee' => 'u.name'
        ])
		->from(['ms' => 'calc_moneystud'])
		->innerJoin(['u' => 'user'], 'u.id = ms.user')
		->innerJoin(['o' => 'calc_office'], 'o.id = ms.calc_office')
		->where([
            'ms.calc_studname' => $this->id,
            'ms.visible' => 1
        ])
		->orderBy(['ms.data' => SORT_DESC])
        ->all();
        
        return $payments;
    }

    public function getLessons()
    {
        $lessons = (new \yii\db\Query())
		->select([
            'lessondate' => 'jg.data',
            'comm' => 'sjg.comments',
            'coursename' => 's.name',
            'level' => 'el.name',
            'teacher' => 't.name',
            'office' => 'o.name',
            'studstatus' => 'sj.name',
            'lessontime' => 'tn.name',
            'studstatusid' => 'sj.id',
            'courseid' => 's.id',
            'lessonid' => 'jg.id',
            'description' => 'jg.description', 
            'homework' => 'jg.homework'
        ]) 
		->from(['sjg' => 'calc_studjournalgroup']) 
		->leftjoin(['gt' => 'calc_groupteacher'], 'gt.id = sjg.calc_groupteacher')
		->leftjoin(['t' => 'calc_teacher'], 't.id=gt.calc_teacher')
		->leftjoin(['el' => 'calc_edulevel'], 'el.id = gt.calc_edulevel')
		->leftjoin(['s' => 'calc_service'], 's.id = gt.calc_service')
		->leftjoin(['o' => 'calc_office'], 'o.id = gt.calc_office ')
		->leftjoin(['sj' => 'calc_statusjournal'], 'sj.id = sjg.calc_statusjournal')
		->leftjoin(['jg' => 'calc_journalgroup'], 'jg.id = sjg.calc_journalgroup')
		->leftjoin(['tn' => 'calc_timenorm'], 'tn.id = s.calc_timenorm')
		->where([
            'sjg.calc_studname' => $this->id,
            'jg.visible' => 1
        ])
        ->andWhere(['!=', 'sjg.user', 0])
		->orderBy(['jg.data' => SORT_DESC])
        ->all();
        
        return $lessons;
    }

    public function getAttestations()
    {
        $grades = (new \yii\db\Query())
        ->select([
            'date' => 'sg.date',
            'score' => 'sg.score',
            'type' => 'sg.type',
            'description' => 'sg.description',
        ])
        ->from(['sg' => 'student_grades'])
        ->where([
            'sg.visible' => 1,
            'sg.calc_studname' => $this->id,
        ])
        ->orderBy(['sg.date' => SORT_DESC])
        ->all();
        
        return $grades;
    }

    public function getPassedLessonsByService()
    {
        $lessons = (new \yii\db\Query())
		->select([
            'lessonattend' => 'count(sjg.id)',
            'serviceid' => 'gt.calc_service',
        ])
		->from(['sjg' => 'calc_studjournalgroup'])
		->innerJoin(['jg' => 'calc_journalgroup'], 'jg.id = sjg.calc_journalgroup')
		->innerJoin(['gt' => 'calc_groupteacher'], 'gt.id = jg.calc_groupteacher and gt.id = sjg.calc_groupteacher')
		->where([
            'sjg.calc_studname' => $this->id,
            'jg.visible' => 1,
            'sjg.calc_statusjournal' => [1, 3],
            'jg.view' => 1,
        ])
        ->groupBy(['serviceid'])
        ->all();
        
        return $lessons;
    }

    public function getOrderedLessonsByService($ids = [])
    {
        $languages = [];
        $mdLanguages = (new \yii\db\Query())
        ->select(['id' => 'id', 'name' => 'name'])
        ->from(['l' => 'calc_lang'])
        ->where([
            'visible' => 1
        ])
        ->all();

        foreach($mdLanguages as $language) {
            $languages[$language['id']] = $language['name'];
        }

        $eduforms = [];
        $mdEduforms = (new \yii\db\Query())
        ->select(['id' => 'id', 'name' => 'name'])
        ->from(['l' => 'calc_eduform'])
        ->where([
            'visible' => 1
        ])
        ->all();

        foreach($mdEduforms as $eduform) {
            $eduforms[$eduform['id']] = $eduform['name'];
        }

        $services = (new \yii\db\Query())
		->select([
            'lessonpaied' => 'sum(i.num)',
            'serviceid' => 's.id',
            'servicename' => 's.name',
            'languageId' => 's.calc_lang',
            'eduformId' => 's.calc_eduform'
        ])
		->from([ 'i' => 'calc_invoicestud'])
		->innerJoin(['s' => 'calc_service'], 's.id = i.calc_service')
		->where([
            'i.calc_studname' => $this->id,
            'i.visible' => 1,
        ])
		->andFilterWhere(['in', 'i.calc_service', $ids])
		->groupBy(['serviceid', 'servicename', 'languageId', 'eduformId'])
		->orderBy(['s.data' => SORT_DESC])
        ->all();

        foreach($services as &$service) {
            $service['languageName'] = $languages[$service['languageId']] ?? '';
            $service['eduformName'] = $eduforms[$service['eduformId']] ?? '';
        }
        
        return $services;
    }
}