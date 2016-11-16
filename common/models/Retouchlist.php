<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%retouchlist}}".
 *
 * @property integer $id
 * @property integer $opttype
 * @property integer $orderid
 * @property integer $picid
 * @property integer $orgpicid
 * @property integer $opterid
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $isvalid
 * @property string $datasource
 * @property string $modifysource
 */
class Retouchlist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%retouchlist}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opttype', 'orderid', 'picid', 'orgpicid', 'opterid', 'created_at', 'updated_at', 'isvalid'], 'integer'],
            [['orderid', 'picid', 'orgpicid', 'opterid', 'created_at', 'updated_at'], 'required'],
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
            'opttype' => Yii::t('app', 'Opttype'),
            'orderid' => Yii::t('app', 'Orderid'),
            'picid' => Yii::t('app', 'Picid'),
            'orgpicid' => Yii::t('app', 'Orgpicid'),
            'opterid' => Yii::t('app', 'Opterid'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'isvalid' => Yii::t('app', 'Isvalid'),
            'datasource' => Yii::t('app', 'Datasource'),
            'modifysource' => Yii::t('app', 'Modifysource'),
        ];
    }
}
