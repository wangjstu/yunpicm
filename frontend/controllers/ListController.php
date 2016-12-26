<?php

namespace frontend\controllers;

use yii;
use frontend\models\ListStatus;

/**
 * 展示类：
 *  * 根据用户id展示历史订单(拍摄、修片、看片)
 *  * 根据订单id展示订单状态(现在状态、历史状态中的照片)
 * Class ListController
 * @package frontend\controllers
 */
class ListController extends \yii\web\Controller
{
    public function actionIndex()
    {
        /*$tmodel = new ListStatus();
        $tres = $tmodel->getOrdersByUserid(Yii::$app->user->id,2);
        foreach ($tres as $order) {
            var_dump($order->attributes);
        }
        exit;*/
        $tmodel = new ListStatus();
        $tres = $tmodel->getOrderPicByOrderid(8,2);
        foreach ($tres as $order) {
            var_dump($order->attributes);
        }
        exit;
        return $this->render('index');
    }

}
