<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%pubtype}}".
 *
 * @property integer $id
 * @property integer $typescope
 * @property integer $parenttypeid
 * @property string $typename
 * @property string $note
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $isvalid
 * @property string $datasource
 * @property string $modifysource
 */
class Pubtype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pubtype}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typescope', 'parenttypeid', 'created_at', 'updated_at', 'isvalid'], 'integer'],
            [['typename', 'created_at', 'updated_at'], 'required'],
            [['note'], 'string'],
            [['typename'], 'string', 'max' => 255],
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
            'typescope' => Yii::t('app', 'Typescope'),
            'parenttypeid' => Yii::t('app', 'Parenttypeid'),
            'typename' => Yii::t('app', 'Typename'),
            'note' => Yii::t('app', 'Note'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'isvalid' => Yii::t('app', 'Isvalid'),
            'datasource' => Yii::t('app', 'Datasource'),
            'modifysource' => Yii::t('app', 'Modifysource'),
        ];
    }

    public static function getOrderType($type=-1)
    {
        $typearr = [
            '个人','多人','不计入收费'
        ];
        if ($type==-1) {
            return $typearr;
        } else {
            return $typearr[$type];
        }
    }

}
