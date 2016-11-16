<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%picstore}}".
 *
 * @property integer $id
 * @property string $storename
 * @property string $storeaddress
 * @property integer $userid
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $isvalid
 * @property string $datasource
 * @property string $modifysource
 */
class Picstore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%picstore}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['storename', 'storeaddress', 'created_at', 'updated_at'], 'required'],
            [['userid', 'created_at', 'updated_at', 'isvalid'], 'integer'],
            [['storename', 'storeaddress'], 'string', 'max' => 255],
            [['datasource', 'modifysource'], 'string', 'max' => 30],
            ['isvalid', 'default', 'value' => 1],
            [['datasource', 'modifysource'], 'default', 'value'=>'PHP'],
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
            'storename' => Yii::t('app', 'Storename'),
            'storeaddress' => Yii::t('app', 'Storeaddress'),
            'userid' => Yii::t('app', 'Userid'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'isvalid' => Yii::t('app', 'Isvalid'),
            'datasource' => Yii::t('app', 'Datasource'),
            'modifysource' => Yii::t('app', 'Modifysource'),
        ];
    }
}
