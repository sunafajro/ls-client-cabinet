<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "calc_message".
 *
 * @property integer $id
 * @property integer $visible
 * @property integer $longmess
 * @property string $name
 * @property string $description
 * @property string $files
 * @property integer $user
 * @property string $data
 * @property integer $send
 * @property integer $calc_messwhomtype
 * @property string $refinement
 * @property integer $refinement_id
 */
class Message extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calc_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user', 'calc_messwhomtype', 'refinement', 'refinement_id'], 'required'],
            [['visible', 'longmess', 'user', 'send', 'calc_messwhomtype', 'refinement_id'], 'integer'],
            [['name', 'description', 'files', 'refinement'], 'string'],
            [['data'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visible' => Yii::t('app', 'Visible'),
            'longmess' => Yii::t('app', 'Is long'),
            'name' => Yii::t('app', 'Subject'),
            'description' => Yii::t('app', 'Text'),
            'files' => Yii::t('app', 'Files'),
            'user' => Yii::t('app','Sender id'),
            'data' => Yii::t('app', 'Date'),
            'send' => Yii::t('app', 'Is sended'),
            'calc_messwhomtype' => Yii::t('app','Receiver type'),
            'refinement' => Yii::t('app','Receiver id'),
            'refinement_id' => Yii::t('app','Receiver name'),
            
        ];
    }

    public function getNews()
    {
        $messages = (new \yii\db\Query())
        ->select('')
        ->from(['m' => static::tableName()])
        ->where([
            'm.calc_messwhomtype' => '12',
            'm.send' => 1
        ])
        ->orderBy(['m.data' => SORT_DESC])
        ->limit(5)
        ->all();

        return $messages;
    }

    public function getMessages()
    {
        $messages = (new \yii\db\Query())
        ->select('')
        ->from(['m' => static::tableName()])
        ->where([
            'm.calc_messwhomtype' => '12',
            'm.send' => 1
        ])
        ->orderBy(['m.data' => SORT_DESC])
        ->limit(5)
        ->all();

        return $messages;
    }
}