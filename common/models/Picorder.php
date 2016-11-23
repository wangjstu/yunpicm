<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%picorder}}".
 *
 * @property integer $id
 * @property integer $originalid
 * @property string $notes
 * @property string $contacts
 * @property string $contacttel
 * @property integer $istodaysee
 * @property integer $ordertype
 * @property integer $orderstatus
 * @property integer $orderpiccount
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $isvalid
 * @property string $datasource
 * @property string $modifysource
 */
class Picorder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%picorder}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isvalid', 'datasource', 'modifysource'],'safe'],
            [['originalid', 'istodaysee', 'ordertype', 'orderstatus', 'orderpiccount'], 'integer'],
            [['notes'], 'string'],
            [['contacts'], 'string', 'max' => 255],
            [['contacttel'], 'string', 'max' => 20],
            [['datasource', 'modifysource'], 'string', 'max' => 30],
            ['isvalid', 'default', 'value' => 1],
            [['datasource','modifysource'], 'default', 'value'=>'PHP'],
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
            'originalid' => Yii::t('app', 'Originalid'),
            'notes' => Yii::t('app', 'Notes'),
            'contacts' => Yii::t('app', 'Contacts'),
            'contacttel' => Yii::t('app', 'Contacttel'),
            'istodaysee' => Yii::t('app', 'Istodaysee'),
            'ordertype' => Yii::t('app', 'Ordertype'),
            'orderstatus' => Yii::t('app', 'Orderstatus'),
            'orderpiccount' => Yii::t('app', 'Orderpiccount'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'isvalid' => Yii::t('app', 'Isvalid'),
            'datasource' => Yii::t('app', 'Datasource'),
            'modifysource' => Yii::t('app', 'Modifysource'),
        ];
    }

    /**
     * 摄影师拍摄记录
     * @return \yii\db\ActiveQuery
     */
    public function getPhotolists()
    {
        //第一个参数为要关联的子表模型类名，
        //第二个参数指定 通过子表的orderid，关联主表的id字段
        //Photolist.orderid <- Picorder.id 关联建立一对多关系
        return $this->hasMany(Photolist::className(), ['orderid'=>'id']);
    }

    /**
     * 修片师记录
     * @return \yii\db\ActiveQuery
     */
    public function getRetouchlists()
    {
        return $this->hasMany(Retouchlist::className(), ['orderid'=>'id']);
    }

    /**
     * 订单照片
     * @return $this
     */
    public function getPictures()
    {
        //Picture.id<-(Photolist.orderid <- Picorder.id).picid
        return $this->hasMany(Picture::className(), ['id'=>'picid'])->via('photolists');
    }
}
