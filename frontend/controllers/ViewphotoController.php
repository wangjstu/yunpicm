<?php

namespace frontend\controllers;

/**
 * Class ViewphotoController
 * @package frontend\controllers
 * 看片师相关操作页面
 */
class ViewphotoController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
