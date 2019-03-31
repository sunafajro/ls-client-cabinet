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
		->select('cjg.data as lessondate, csjg.comments as comm, cs.name as coursename, cel.name as level, ctch.name as teacher, co.name as office, csj.name as studstatus, ctn.name as lessontime, csj.id as studstatusid, cs.id as courseid, cjg.id as lessonid, cjg.description as description, cjg.homework as homework') 
		->from('calc_studjournalgroup csjg') 
		->leftjoin('calc_groupteacher cgt', 'cgt.id = csjg.calc_groupteacher')
		->leftjoin('calc_teacher ctch', 'ctch.id=cgt.calc_teacher')
		->leftjoin('calc_edulevel cel', 'cel.id = cgt.calc_edulevel')
		->leftjoin('calc_service cs', 'cs.id = cgt.calc_service')
		->leftjoin('calc_office co', 'co.id = cgt.calc_office ')
		->leftjoin('calc_statusjournal csj', 'csj.id = csjg.calc_statusjournal')
		->leftjoin('calc_journalgroup cjg', 'cjg.id = csjg.calc_journalgroup')
		->leftjoin('calc_timenorm ctn', 'ctn.id = cs.calc_timenorm')
		->where([
            'csjg.calc_studname' => $this->id,
            'cjg.visible' => 1
        ])
        ->andWhere(['!=', 'csjg.user', '0'])
		->orderBy(['cjg.data' => SORT_DESC])
        ->all();
        
        return $lessons;
    }
}