<?php

namespace  console\controllers;

use yii\console\Controller;
use common\models\User;

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

    public function actionCreate($username, $password, $email='admin@admin.com')
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();

        if ($user->validate() && $user->save()) {
            echo "创建用户成功 username:" . $username . " password: " . $password . " 成功" . PHP_EOL;
            return 0;
        } else {
            echo "创建用户成功 username:" . $username . " password: " . $password . " 失败" .PHP_EOL;
            echo "error:" . $user->errors . PHP_EOL;
            return 1;
        }
    }
}