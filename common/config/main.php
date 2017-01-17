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
            'cachePath' => '@runtime/filecache'
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
        ],
        /*'queue' => [
            'class' => 'shmilyzxt\queue\queues\DatabaseQueue', //队列使用的类
            'jobEvent' => [ //队列任务事件配置，目前任务支持2个事件
                'on beforeExecute' => ['shmilyzxt\queue\base\JobEventHandler','beforeExecute'],
                'on beforeDelete' => ['shmilyzxt\queue\base\JobEventHandler','beforeDelete'],
            ],
            'connector' => [//队列中间件链接器配置（这是因为使用数据库，所以使用yii\db\Connection作为数据库链接实例）
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=127.0.0.1;dbname=yumpicm',
                'username' => 'root',
                'password' => 'sunshine',
                'charset' => 'utf8',
            ],
            'table' => 'jobs', //存储队列数据表名
            'queue' => 'default', //队列的名称
            'expire' => 60, //任务过期时间
            'maxJob' =>0, //队列允许最大任务数，0为不限制
            'failed' => [//任务失败日志记录（目前只支持记录到数据库）
                'logFail' => true, //开启任务失败处理
                'provider' => [ //任务失败处理类
                    'class' => 'shmilyzxt\queue\failed\DatabaseFailedProvider',
                    'db' => [ //数据库链接
                        'class' => 'yii\db\Connection',
                        'dsn' => 'mysql:host=127.0.0.1;dbname=yumpicm',
                        'username' => 'root',
                        'password' => 'sunshine',
                        'charset' => 'utf8',
                    ],
                    'table' => 'failed_jobs' //存储失败日志的表名
                ],
            ],
        ]*/
    ],
];
