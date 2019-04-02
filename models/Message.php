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
            [['name', 'description', 'user', 'calc_messwhomtype', 'refinement_id'], 'required'],
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
            'name' => Yii::t('app', 'Message subject'),
            'description' => Yii::t('app', 'Message text'),
            'files' => Yii::t('app', 'Files'),
            'user' => Yii::t('app','Sender id'),
            'data' => Yii::t('app', 'Date'),
            'send' => Yii::t('app', 'Is sended'),
            'calc_messwhomtype' => Yii::t('app','Receiver type'),
            'refinement' => Yii::t('app','Receiver name'),
            'refinement_id' => Yii::t('app','Receiver id'),
            
        ];
    }
}