<?php
namespace app\models;
use Yii;
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
            'username' => $student['username'],
            'password' => $student['password'],
            'authKey' => '',
            'accessToken' => '',
            'isActive' => $student['active'],
            'lastLoginDate' => $student['date']
        ] : null;
    }
}