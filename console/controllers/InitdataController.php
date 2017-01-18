<?php

namespace  console\controllers;

use yii\console\Controller;

class InitdataController extends Controller
{
    // 这个命令 "yii example/create test" 将调用 "actionCreate('test')"
    //public function actionCreate($name) { ... }

    // 这个命令 "yii example/index city" 将调用 "actionIndex('city', 'name')"
    // 这个命令 "yii example/index city id" 将调用 "actionIndex('city', 'id')"
    //public function actionIndex($category, $order = 'name') { ... }

    // 这个命令 "yii example/add test" 将调用 "actionAdd(['test'])"
    // 这个命令 "yii example/add test1,test2" 将调用 "actionAdd(['test1', 'test2'])"
    //public function actionAdd(array $name) { ... }

    //return 1; //异常
    //return 0; //正常

    //yii migrate/create --migrationTable=my_migration
    //[[yii\console\controllers\MigrateController::actionCreate()|MigrateController::actionCreate()]] 中的 [[yii\console\controllers\MigrateController::$migrationTable|MigrateController::$migrationTable]] 属性可以用下面的方法来设置

    public function actionCreate()
    {
        echo 1;
        return 0;
    }
}