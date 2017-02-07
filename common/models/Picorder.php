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
        //@http://yii2.techbrood.com/guide-active-record.html
        return $this->hasMany(Photolist::className(), ['orderid'=>'id'])->where('photolist.isvalid=:in_isvalid', ['in_isvalid'=>1]);
    }

    /**
     * 修片师记录
     * @return \yii\db\ActiveQuery
     */
    public function getRetouchlists()
    {
        return $this->hasMany(Retouchlist::className(), ['orderid' => 'id'])->where('retouchlist.opttype=:retouchtype and retouchlist.isvalid=:in_isvalid', [':retouchtype' => Retouchlist::RETOUCHLIST_XIUPIAN, 'in_isvalid'=>1]);
    }

    /**
     * 看片师记录
     * @return \yii\db\ActiveQuery
     */
    public function getViewphotolists()
    {
        return $this->hasMany(Retouchlist::className(), ['orderid' => 'id'])->where('retouchlist.opttype=:retouchtype and retouchlist.isvalid=:in_isvalid', [':retouchtype' => Retouchlist::RETOUCHLIST_KANPIAN, 'in_isvalid'=>1]);
    }

    /**
     * 订单原照片
     * @return $this
     */
    public function getPictures()
    {
        //Picture.id<-(Photolist.orderid <- Picorder.id).picid
        return $this->hasMany(Picture::className(), ['id' => 'picid'])->where('picture.isvalid=:in_isvalid', ['in_isvalid'=>1])->via('photolists');
    }

    /**
     * 订单修片照片
     * @return $this
     */
    public function getRetouchpictures()
    {
        //Picture.id<-(Photolist.orderid <- Picorder.id).picid
        return $this->hasMany(Picture::className(), ['id' => 'picid'])->where('picture.isvalid=:in_isvalid', ['in_isvalid'=>1])->via('retouchlists');
    }

    /**
     * 订单看片师修片照片
     * @return $this
     */
    public function getViewpictures()
    {
        //Picture.id<-(Photolist.orderid <- Picorder.id).picid
        return $this->hasMany(Picture::className(), ['id' => 'picid'])->where('picture.isvalid=:in_isvalid', ['in_isvalid'=>1])->via('viewphotolists');
    }

    const OS_ORDER_FAILD = -1; //订单失败
    const OS_ORDER_READY_PHOTO = 0; //待拍摄
    const OS_ORDER_READY_RETOUCH = 1; //待修片
    const OS_ORDER_RETOUCHING = 2; //修片中..不会记录到数据库中，使用ServiceSupport中的orderlock设置该状态
    const OS_ORDER_READY_VIEW = 3; //待看片
    const OS_ORDER_VIEWING = 4; //看片中..不会记录到数据库中，使用ServiceSupport中的orderlock设置该状态
    const OS_ORDER_SUCCESS = 5; //订单结束

    /**
     * 获取订单状态
     * @return array
     */
    public static function orderStatus($status=0, $isAll=false)
    {
        $statusArr = [
            '-1'=>'订单失败',
            '0'=>'待拍摄',
            '1'=>'待修片', //拍摄完毕
            '2'=>'修片中',
            '3'=>'待看片', //修片完毕
            '4'=>'看片中',
            '5'=>'订单结束', //订单成功
        ];
        return $isAll ? $statusArr:$statusArr[$status];
    }


    /**
     * 结合库表及cache打印出订单状态
     * @param $orderid
     * @param $status 库中数据
     * @return array
     */
    public static function printOrderStatus($orderid, $status, $isidnum=false)
    {
        return Yii::$app->ServiceSupport->isLockOrder($orderid, $status+1) ? ($isidnum ? $status+1 :self::orderStatus($status+1)) : ($isidnum ? $status : self::orderStatus($status));
    }
}
