<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        "admin" => [
            "class" => "mdm\admin\Module"
        ],
    ],
    "aliases" => [
        "@mdm/admin" => "@vendor/mdmsoft/yii2-admin"
    ],
    "as access" => [
        "class" => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            //这里是允许访问的action， *表示允许所有
            //controller/action
            '*'
        ]
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=yumpicm',
            'username' => 'root',
            'password' => 'sunshine',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'authManager' => [
            //components数组中加入authManager组件,有PhpManager和DbManager两种方式,
            //PhpManager将权限关系保存在文件里,这里使用的是DbManager方式,将权限关系保存在数据库.
            "class" => 'yii\rbac\DbManager',
            "defaultRoles" => ["guest"],
        ],
        'ServiceSupport' =>[  //网站展示工具类
            'class' => 'common\components\ServiceSupport'
        ],
        'PictureTool' => [ //图片相关操作工具类
            'class' => 'common\components\PictureTool'
        ]
    ],
];
