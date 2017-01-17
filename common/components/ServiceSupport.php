<?php
namespace common\components;

use common\models\Picorder;
use Yii;
use yii\base\Component;
/**
 * Created by PhpStorm.
 * User: wangjstu
 * Date: 2016/10/15
 * Time: 15:07
 */

class ServiceSupport extends Component
{
    public function powered()
    {
        return Yii::t('app', 'Powered by {yumpicm}', [
            'yumpicm' => '<a href="#" rel="external">' . \Yii::t('app',
                'yumpicm') . '</a>'
        ]);
    }

    /**
     * 看片中...对订单和用户进行加锁
     * @param $orderid
     * @param $userid
     * @param $status  订单进入哪个状态,就锁哪个状态
     * @return bool
     */
    public function PicLock($orderid, $userid, $status=Picorder::OS_ORDER_READY_RETOUCH, $returnValue=false)
    {
        $pre = 'LOCK_'.$status.'_';
        $lockData = ['orderid'=>$orderid, 'userid'=>$userid, 'time'=>time(), 'orderstatus'=>$status];
        Yii::$app->cache->set($pre.'ORDERID_'.$orderid, $lockData);
        Yii::$app->cache->set($pre.'USERID_'.$userid, $lockData);
        return $returnValue ? $lockData : true;
    }

    /**
     * 检测订单是否正在被操作中
     * @param $orderid
     * @param $status  订单进入哪个状态,就锁哪个状态
     * @return bool
     */
    public function isLockOrder($orderid, $status=Picorder::OS_ORDER_READY_RETOUCH, $returnValue=false)
    {
        $pre = 'LOCK_'.$status.'_';
        $orderlock = Yii::$app->cache->get($pre.'ORDERID_'.$orderid);
        return $orderlock ? ($returnValue ? $orderlock : true) : false;
    }

    /**
     * 检测用户是否正在进行某订单操作
     * @param $userid
     * @param int $status  订单进入哪个状态,就锁哪个状态
     * @return bool
     */
    public function isLockUser($userid, $status=Picorder::OS_ORDER_READY_RETOUCH, $returnValue=false)
    {
        $pre = 'LOCK_'.$status.'_';
        $userlock = Yii::$app->cache->get($pre.'USERID_'.$userid);
        return $userlock ? ($returnValue ? $userlock : true) : false;
    }

    /**
     * 删除锁
     * @param $orderid
     * @param $userid
     * @param int $status 订单进入哪个状态,就锁哪个状态
     */
    public function clearLock($orderid, $userid, $status=Picorder::OS_ORDER_READY_RETOUCH)
    {
        $pre = 'LOCK_'.$status.'_';
        Yii::$app->cache->delete($pre.'ORDERID_'.$orderid);
        Yii::$app->cache->delete($pre.'USERID_'.$userid);
    }


    /**
     * 获取用户正在处理的订单
     * @param $userid
     * @return array
     */
    public function getUserOperatingOrder($userid)
    {
        $allStatus = Picorder::orderStatus(0, true);
        $res = [];
        foreach ($allStatus as $key=>$val) {
            $lockkey = 'LOCK_'.$key.'_USERID_'.$userid;
            $tmpcacheval = Yii::$app->cache->get($lockkey);
            if ($tmpcacheval) {
                $res[$key] = $tmpcacheval;
            }
        }
        return $res;
    }

}