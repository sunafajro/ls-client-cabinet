<?php
namespace app\models;

use Yii;

use yii\data\Pagination;
use yii\data\ActiveDataProvider;

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

    public function getNews()
    {
        $query = (new \yii\db\Query())
        ->select([
            'date' => 'm.data',
            'files' => 'm.files',
            'id' => 'm.id',
            'text' => 'm.description',
            'title' => 'm.name',
        ])
        ->from(['m' => 'calc_message'])
        ->where([
            'm.calc_messwhomtype' => '12',
            'm.send' => 1,
            'm.visible' => 1,
        ])
        ->orderBy(['m.data' => SORT_DESC]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
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
            'lessonAttend' => 'count(sjg.id)',
            'serviceId' => 'gt.calc_service',
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
            'lessonPaied' => 'sum(i.num)',
            'serviceId' => 's.id',
            'serviceName' => 's.name',
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

    public function getSchedule()
    {
		$schedule = (new \yii\db\Query())
		->select([
            'coursename' => 'l.name',
            'denname' => 'd.name',
            'starttime' => 'sc.time_begin',
            'endtime' => 'sc.time_end',
            'office' => 'o.name',
            'cabinet' => 'c.name',
            'teachername' => 't.name'
        ])
		->from(['s' => 'calc_service'])
		->innerJoin(['gt' => 'calc_groupteacher'], 's.id = gt.calc_service')
		->innerJoin(['t' => 'calc_teacher'], 't.id = gt.calc_teacher')
		->innerJoin(['sc' => 'calc_schedule'], 'sc.calc_teacher = t.id AND sc.calc_groupteacher = gt.id')
		->innerJoin(['d' => 'calc_denned'], 'd.id = sc.calc_denned')
		->innerJoin(['o' => 'calc_office'], 'o.id = sc.calc_office')
		->innerJoin(['c' => 'calc_cabinetoffice'], 'sc.calc_cabinetoffice = c.id')
		->innerJoin(['sg' => 'calc_studgroup'], 'gt.id = sg.calc_groupteacher')
		->innerJoin(['sn'=> 'calc_studname'], 'sn.id = sg.calc_studname')
		->innerJoin(['l' => 'calc_lang'], 'l.id = s.calc_lang')
		->where([
            'sn.id' => $this->id,
            'gt.visible' => 1,
            'sg.visible' => 1
        ])
		->orderBy([
            'd.id' => SORT_ASC,
            'sc.time_begin' => SORT_ASC
        ])
        ->all();
        
        return $schedule;
    }

    public function getMessages()
    {
        $query = (new \yii\db\Query())
        ->select([
            'date' => 'm.data',
            'sender' => 'u1.name',
            'receiver' => 'u2.name',
            'text' => 'm.description',
            'title' => 'm.name',
            'type' => 'm.calc_messwhomtype',
        ])
        ->from(['m' => 'calc_message'])
        ->leftjoin(['u1' => 'user'], 'u1.id = m.user')
		->leftjoin(['u2' => 'user'], 'u2.id = m.refinement_id')
        ->where([
            'm.send' => 1,
            'm.visible' => 1,
        ])
        ->andWhere([
            'or',
            ['m.calc_messwhomtype' => 100, 'user' => $this->id],
            ['m.calc_messwhomtype' => 13, 'm.refinement_id' => $this->id],
        ])
        ->orderBy(['m.data' => SORT_DESC]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }

    public function calculateBalance($dolg2 = 0, $services = [], $lessons = [])
    {
        $dolg1 = 0;
        $totalpayedlessons = 0;
        $totalattendedlessons = 0;
        foreach ($services as $lesspaied) {
            $cost = (new \yii\db\Query())
            ->select(['value' => '(i.value / i.num)'])
            ->from(['i' => 'calc_invoicestud'])
            ->where([
                'i.calc_studname' => $this->id,
                'i.visible' => 1,
                'i.calc_service' => $lesspaied['serviceId']
            ])
            ->orderBy(['i.id' => SORT_DESC])
            ->limit(1)
            ->one();
            foreach ($lessons as $lessinfo) {
                $lesscount = 0;
                $servdolg = 0;
                $totalcost = 0;    
                if ((int)$lesspaied['serviceId'] === (int)$lessinfo['serviceId']) {
                    $totalpayedlessons += $lesspaied['lessonPaied'];
                    $totalattendedlessons += $lessinfo['lessonAttend'];
                    $lesscount = $lesspaied['lessonPaied'] - $lessinfo['lessonAttend'];
                    if ($lesscount < 0) {
                        // считаем и присваиваем стоимость урока
                        $totalcost = $cost['value'];
                        // считаем сумму за все уроки одной группы
                        $servdolg = $lesscount * $totalcost;
                    }
                }
                // считаем сумму по всем группам
                $dolg1 = $dolg1 + $servdolg;
            }
        }
        return $dolg1 + $dolg2;
    }

    public function availableMessageReceiversList()
    {
		//выбираем руководителей
		$chiefs = (new \yii\db\Query())
		->select(['uid' => 'u.id', 'title' => 'u.name'])
		->from(['u' => 'user'])
		->where([
            'u.visible' => 1,
            'u.status' => 3,
            'u.id' => [7, 41],
        ])
		->orderBy(['u.id' => SORT_ASC])
		->all();

		//выбираем список преподавателей активных курсов
		$teachers = (new \yii\db\Query())
		->select('ctch.name as title, u.id as uid')
		->from('calc_studname csn')
		->leftjoin('calc_studgroup csg', 'csn.id=csg.calc_studname')
		->leftjoin('calc_groupteacher cgt', 'cgt.id=csg.calc_groupteacher')
		->leftjoin('calc_service csv', 'csv.id=cgt.calc_service')
		->leftjoin('calc_lang cl', 'cl.id=csv.calc_lang')
		->leftjoin('calc_teacher ctch', 'ctch.id=cgt.calc_teacher')
		->leftjoin('user u', 'u.calc_teacher=ctch.id')
		->leftjoin('status st', 'u.status=st.id')
		->where([
            'csn.id' => $this->id,
            'cgt.visible' => 1,
            'u.visible' => 1,
        ])
		->orderBy(['u.id' => SORT_ASC])
		->all();
		
		//выбираем список менеджеров офисов где провоходят занятия активных курсов 
		$managers = (new \yii\db\Query())
		->select('u.id as uid, u.name as title')
		->from('calc_studname csn')
		->leftjoin('calc_studgroup csg', 'csn.id=csg.calc_studname')
		->leftjoin('calc_groupteacher cgt', 'cgt.id=csg.calc_groupteacher')
		->leftjoin('calc_service csv', 'csv.id=cgt.calc_service')
		->leftjoin('calc_office cof', 'cof.id = cgt.calc_office')
		->leftjoin('user u', 'u.calc_office=cof.id')
		->leftjoin('status st', 'u.status=st.id')
		->where([
            'csn.id' => $this->id,
            'cgt.visible' => 1,
            'u.visible' => 1,
        ])
		->orderBy(['cgt.data' => SORT_DESC])
		->all();

		$allusers = array_merge($chiefs, $teachers, $managers);
		$users = [];
		foreach ($allusers as $user){
			$users[$user['uid']] = $user['title'];
		}
		return array_unique($users);
    }
}