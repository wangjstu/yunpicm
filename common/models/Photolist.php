<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%photolist}}".
 *
 * @property integer $id
 * @property integer $orderid
 * @property integer $picid
 * @property integer $userid
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $isvalid
 * @property string $datasource
 * @property string $modifysource
 */
class Photolist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%photolist}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderid', 'picid'], 'safe'],
            [['userid'], 'required'],
            [['userid'], 'integer'],
            [['datasource', 'modifysource'], 'string', 'max' => 30],
            ['isvalid', 'default', 'value' => 1],
            [['datasource', 'modifysource'], 'default', 'value'=>'PHP'],
            [['orderid'], 'exist',
                'skipOnError' => true,
                'targetClass' => Picorder::className(),
                'targetAttribute' => ['orderid'=>'id']],
            [['picid'], 'exist',
                'skipOnError' => true,
                'targetClass' => Picture::className(),
                'targetAttribute' => ['picid'=>'id']]
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
            'orderid' => Yii::t('app', 'Orderid'),
            'picid' => Yii::t('app', 'Picid'),
            'userid' => Yii::t('app', 'Userid'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'isvalid' => Yii::t('app', 'Isvalid'),
            'datasource' => Yii::t('app', 'Datasource'),
            'modifysource' => Yii::t('app', 'Modifysource'),
        ];
    }

    public function getOrder()
    {
        //Picorder.id -> Photolist.orderid 关联建立一对一关系
        return $this->hasOne(Picorder::className(), ['id'=>'orderid']);
    }

    public function getPicture()
    {
        //Picture.id -> Photolist.picid 关联建立一对一关系
        return $this->hasOne(Picture::className(), ['id'=>'picid']);
    }
}
