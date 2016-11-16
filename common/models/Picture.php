<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%picture}}".
 *
 * @property integer $id
 * @property string $picname
 * @property string $picdir
 * @property string $notes
 * @property integer $picsavetype
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $isvalid
 * @property string $datasource
 * @property string $modifysource
 */
class Picture extends \yii\db\ActiveRecord
{
    public $picfile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%picture}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['picfile'], 'safe'],
            [['picfile'], 'file', 'extensions'=>Yii::getAlias('@uploadfileextensions'), 'mimeTypes' => Yii::getAlias('@uploadfilemimeTypes')],
            [['picfile'], 'file', 'maxFiles' => Yii::getAlias('@uploadmaxSize')*1024*1024,
                'tooBig'=>Yii::t('app', 'uploadFileTooLarge {uploadmaxSize} tips',array('uploadmaxSize'=>Yii::getAlias('@uploadmaxSize')))],
            [['notes'], 'string'],
            [['picsavetype'], 'integer'],
            [['picname', 'picdir'], 'string', 'max' => 255],
            [['datasource', 'modifysource'], 'string', 'max' => 30],
            ['isvalid', 'default', 'value' => 1],
            [['datasource', 'modifysource'], 'default', 'value'=>'PHP']
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'picname' => Yii::t('app', 'Picname'),
            'picdir' => Yii::t('app', 'Picdir'),
            'notes' => Yii::t('app', 'Notes'),
            'picsavetype' => Yii::t('app', 'Picsavetype'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'isvalid' => Yii::t('app', 'Isvalid'),
            'datasource' => Yii::t('app', 'Datasource'),
            'modifysource' => Yii::t('app', 'Modifysource'),
        ];
    }

}
