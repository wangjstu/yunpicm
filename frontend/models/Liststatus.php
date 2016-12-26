<?php
/**
 * Created by PhpStorm.
 * User: wangjstu
 * Date: 2016/12/11
 * Time: 22:29
 * 展示类：
 *  * 根据用户id展示历史订单(拍摄、修片、看片)
 *  * 根据订单id展示订单状态(现在状态、历史状态中的照片)
 */
namespace  frontend\models;

use yii;
use yii\base\Model;
use common\models\Picorder;
use common\models\Retouchlist;
use common\models\Picture;

class ListStatus extends Model
{

    /**
     * 根据用户id获取历史订单(包括拍摄、修片、看片)
     */
    public function getOrdersByUserid($userid, $type=0)
    {
        $res = [];
        switch ($type) {
            case Retouchlist::RETOUCHLIST_XIUPIAN: //修片
                $res = Picorder::find()->joinWith('retouchlists')->where(['picorder.isvalid'=>1])->all();
                break;
            case Retouchlist::RETOUCHLIST_KANPIAN: //看片
                $res = Picorder::find()->joinWith('viewphotolists')->where(['picorder.isvalid'=>1])->all();
                break;
            default:
                $res = Picorder::find()->joinWith('photolists')->where(['picorder.isvalid'=>1])->all();
                break;
        }
        return $res;
    }

    /**
     * 根据订单id查看订单详情(现在状态、历史状态中的照片)
     */
    public function getOrderPicByOrderid($orderid, $type=0, $userid=0)
    {
        $res = [];
        $userid = empty($userid) ? Yii::$app->user->id : $userid;
        switch ($type) {
            case Retouchlist::RETOUCHLIST_XIUPIAN: //修片
                $res = Picture::find()
                    ->select('picture.*')
                    ->leftJoin('retouchlist', '`retouchlist`.`picid` = `picture`.`id`')
                    ->where(['retouchlist.opttype' => Retouchlist::RETOUCHLIST_XIUPIAN, 'retouchlist.orderid'=> $orderid,'retouchlist.opterid'=>$userid])
                    ->all();
                break;
            case Retouchlist::RETOUCHLIST_KANPIAN: // 看片
                $res = Picture::find()
                    ->select('picture.*')
                    ->leftJoin('retouchlist', '`retouchlist`.`picid` = `picture`.`id`')
                    ->where(['retouchlist.opttype' => Retouchlist::RETOUCHLIST_KANPIAN, 'retouchlist.orderid'=> $orderid, 'retouchlist.opterid'=>$userid])
                    ->all();
                break;
            default:
                $res = Picture::find()
                    ->select('picture.*')
                    ->leftJoin('photolist', '`photolist`.`picid` = `picture`.`id`')
                    ->where(['photolist.orderid'=> $orderid, 'photolist.userid'=>$userid])
                    ->all();
                break;
        }
        return $res;
    }

}