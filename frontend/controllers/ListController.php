<?php

namespace frontend\controllers;

use common\models\Picorder;
use yii;
use frontend\models\ListStatus;
use common\models\Retouchlist;

/**
 * 展示类：
 *  * 根据用户id展示历史订单(拍摄、修片、看片)
 *  * 根据订单id展示订单状态(现在状态、历史状态中的照片)
 * Class ListController
 * @package frontend\controllers
 */
class ListController extends \yii\web\Controller
{
    /**
     * 处理中的订单 只展示在cache中的订单--状态为
     * Picorder::OS_ORDER_RETOUCHING=1;Picorder::OS_ORDER_VIEWING=3; 的订单
     * @return string
     */
    public function actionOperating()
    {
        $userOperatingOrders = Yii::$app->ServiceSupport->getUserOperatingOrder(Yii::$app->user->id);
        return $this->render('operating',[
            'operatingdata'=>$userOperatingOrders
        ]);
    }

    /**
     * 获取历史订单(个人)
     * @return string
     */
    public function actionHistoryOrder($type=0, $pagesize=10)
    {
        $model = new ListStatus();
        $res = $model->getOrdersByUserid(Yii::$app->user->id, $type, $pagesize);
        return $this->render('historyorder', [
            'models' => $res['model'],
            'usertype' => $res['usertype'],
            'pages' => $res['pages']
        ]);
    }


    /**
     * 获取需要修片或者需要看片的订单列表-分页展示所有
     * @param int $status
     * *************************************
     * '-1'=>'订单失败',
     * '0'=>'待拍摄',
     * '1'=>'待修片', //拍摄完毕
     * '2'=>'修片中',
     * '3'=>'待看片', //修片完毕
     * '4'=>'看片中',
     * '5'=>'订单结束', //订单成功
     * **************************************
     * @param int $pagesize
     * @return string
     */
    public function actionReadyOrder($status=1, $pagesize=10)
    {
        $model = new ListStatus();
        $res = $model->getOrdersByStatus($status, $pagesize);
        return $this->render('readyorder', [
            'models' => $res['model'],
            'pages' => $res['pages'],
            'title' => Picorder::orderStatus($status)
        ]);
    }


    /**
     * 接单(接待修片单，接待看片单)
     */
    public function actionOperateOrder($orderid, $startStatus)
    {
        $lockStatus = $startStatus + 1;
        $isOrderLock = Yii::$app->ServiceSupport->isLockOrder($orderid, $lockStatus, true);
        $isUserLock = Yii::$app->ServiceSupport->isLockUser(Yii::$app->user->id , $lockStatus, true);
        if ($isOrderLock ) {
            if ($isOrderLock['userid']==Yii::$app->user->id) {
                $infoMsg = '重复接单，该订单已经被您接到';
                Yii::$app->getSession()->setFlash('info', '接单失败..[' . $infoMsg .']');
                if ($isOrderLock['orderstatus'] == Picorder::OS_ORDER_READY_RETOUCH) {
                    return $this->redirect(['repairphoto/update', 'id'=>$orderid]); //跳到修片
                } elseif ($isOrderLock['orderstatus'] == Picorder::OS_ORDER_READY_VIEW) {
                    return $this->redirect(['viewphoto/update', 'id'=>$orderid]); //跳到看片
                } else {
                    Yii::$app->getSession()->setFlash('info', '接单失败..[' . $infoMsg .']');
                    return $this->goBack();
                }
            } else {
                $infoMsg = '该定单已被别人接单';
                Yii::$app->getSession()->setFlash('info', '接单失败..[' . $infoMsg .']');
                return $this->goBack();
            }
        } elseif ($isUserLock) {
            $infoMsg = '你已接单中,需处理完毕后才能接单';
            if ($isUserLock['orderstatus'] == Picorder::OS_ORDER_READY_RETOUCH) {
                return $this->redirect(['repairphoto/update', 'id'=>$isUserLock['orderid']]); //跳到修片
            } elseif ($isUserLock['orderstatus'] == Picorder::OS_ORDER_READY_VIEW) {
                return $this->redirect(['viewphoto/update', 'id'=>$isUserLock['orderid']]); //跳到看片
            } else {
                Yii::$app->getSession()->setFlash('info', '接单失败..[' . $infoMsg .']');
                return $this->goBack();
            }
        } else {
            $infoData = Yii::$app->ServiceSupport->PicLock($orderid, Yii::$app->user->id, $lockStatus, true);
            if ($infoData) {
                Yii::$app->getSession()->setFlash('info', '接单成功');
                if ($startStatus == Picorder::OS_ORDER_READY_RETOUCH) {
                    return $this->redirect(['repairphoto/update', 'id'=>$orderid]); //跳到修片
                } elseif ($startStatus == Picorder::OS_ORDER_READY_VIEW) {
                    return $this->redirect(['viewphoto/update', 'id'=>$orderid]); //跳到看片
                } else {
                    return $this->render('operateorder',[
                        'infoData'=>$infoData
                    ]);
                }
            } else {
                Yii::$app->getSession()->setFlash('info', '接单异常');
                return $this->goBack();
            }
        }
    }

    /**
     * 展示订单结果(最终/中间)--查看订单使用
     * @param $orderid
     * @return string
     */
    public function actionShowDetail($orderid)
    {
        $model = new ListStatus();
        $orderData = $model->getOrderDetails($orderid);
        $photoData = $model->getOrderPicByOrderid($orderid, 0, Yii::$app->user->id);
        $retouchData = $model->getOrderPicByOrderid($orderid, Retouchlist::RETOUCHLIST_XIUPIAN, Yii::$app->user->id);
        $viewData = $model->getOrderPicByOrderid($orderid, Retouchlist::RETOUCHLIST_KANPIAN, Yii::$app->user->id);
        return $this->render('showdetail',[
            'orderdata' => $orderData,
            'photodata' => $photoData,
            'retouchdata' => $retouchData,
            'viewdata' => $viewData
        ]);
    }

}
