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
use common\models\Photolist;
use yii\data\Pagination;

class ListStatus extends Model
{

    /**
     * 根据用户id获取历史订单(包括拍摄、修片、看片)
     * @param $userid
     * @param int $type
     * @param int $pagesize
     * @return array
     */
    public function getOrdersByUserid($userid, $type=0, $pagesize=10)
    {
        switch ($type) {
            case Retouchlist::RETOUCHLIST_XIUPIAN: //修片
                $data = Picorder::find()->joinWith('retouchlists')->where(['picorder.isvalid'=>1, 'retouchlist.opterid'=>$userid])->orderBy('picorder.created_at DESC')->distinct();
                $usertype = '修片';
                break;
            case Retouchlist::RETOUCHLIST_KANPIAN: //看片
                $data = Picorder::find()->joinWith('viewphotolists')->where(['picorder.isvalid'=>1, 'retouchlist.opterid'=>$userid])->orderBy('picorder.created_at DESC')->distinct();
                $usertype = '看片';
                break;
            default: //拍摄
                $data = Picorder::find()->joinWith('photolists')->where(['picorder.isvalid'=>1, 'photolist.userid'=>$userid])->orderBy('picorder.created_at DESC')->distinct();
                $usertype = '拍摄';
                break;
        }
        $pages = new Pagination([
            'totalCount' =>$data->count(),
            'pageSize' => $pagesize,
            'pageSizeParam' => false, //默认带的有每页的数量per-page 如果你不想显示该参数，设置pageSizeParam=false
            'pageParam' => 'p', //默认的页面取决于参数page,如果你想改变该参数为p,设置pageParam=p
            //'validatePage' => false, //分页验证
        ]);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();
        return ['model'=>$model, 'pages'=>$pages, 'usertype'=>$usertype];
    }

    /**
     * 根据订单状态获取订单-主要用户获取待修片、待看片的记录
     * @param int $status
     * @param int $pagesize
     * @return array
     */
    public function getOrdersByStatus($status=0, $pagesize=10)
    {
        $data = Picorder::find()->where(['picorder.isvalid'=>1, 'picorder.orderstatus'=>$status])->orderBy('picorder.created_at DESC');
        $pages = new Pagination([
            'totalCount' =>$data->count(),
            'pageSize' => $pagesize,
            'pageSizeParam' => false, //默认带的有每页的数量per-page 如果你不想显示该参数，设置pageSizeParam=false
            'pageParam' => 'p', //默认的页面取决于参数page,如果你想改变该参数为p,设置pageParam=p
            //'validatePage' => false, //分页验证
        ]);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();
        return ['model'=>$model, 'pages'=>$pages];
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


    /**
     * 获取订单详情
     * @param $orderid
     * @param int $isvalid
     * @return array|null|yii\db\ActiveRecord
     */
    public function getOrderDetails($orderid, $isvalid=1)
    {
        $orderDetail = Picorder::find()
            ->select('picorder.*')
            ->where(['picorder.isvalid'=>$isvalid, 'picorder.id'=>$orderid])
            ->one();
        return $orderDetail;
    }

}