<?php

namespace frontend\controllers;

use yii;
use common\models\Picorder;
use common\models\Picture;
use common\models\Photolist;
use frontend\models\Photographer;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\HttpException;

/**
 * Class PhotographerController
 * @package frontend\controllers
 * 摄影师相关操作页面
 */
class PhotographerController extends \yii\web\Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //only 选项指明 ACF 应当只 对 login， logout 和 signup 方法起作用
                'only' => ['index', 'create', 'update'],
                'rules' => [
                    [
                        'actions' => ['create', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        //@ 是一个特殊标识， 代表"已认证用户"。
                        //? 是另一个特殊的标识，代表"访客用户"
                        'roles' => ['@'],
                    ],
                ],
            ],
            //指定该规则用于匹配哪种请求方法（例如GET，POST）。 这里的匹配大小写不敏感
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 新建
     * @return string|yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Photographer();
        $model->Picorder = new Picorder();
        $model->Picorder->loadDefaultValues();

        if (Yii::$app->request->isPost) {
            $postdata = Yii::$app->request->post();
            $postdata['Pictures'] = array();
            $postdata['Photolists'] = array();
            $postdata = $this->getPostFileAndSave($postdata);
            $model->setAttributes($postdata);
            if ($model->validate() && $model->save()) {
                Yii::$app->getSession()->setFlash('success', '数据保存成功!');
                return $this->redirect(['update', 'id'=>$model->Picorder->id]);
            } else {
                Yii::$app->getSession()->setFlash('failed', '数据保存失败!');
            }
        }
        return $this->render('create',[
            'model' => $model
        ]);
    }


    /**
     * 更新
     * @param $id
     * @return string|yii\web\Response
     * @throws HttpException
     */
    public function actionUpdate($id)
    {
        $model = new Photographer();
        $model->Picorder = Picorder::findOne($id);
        if ($model->Picorder == null) {
            throw new HttpException(404, 'There is not this id ('.$this->getRoute().')');
        }

        if (Yii::$app->request->isPost) {
            $postdata = Yii::$app->request->post();
            $postdata['Pictures'] = $model->Pictures;
            $postdata['Photolists'] = $model->Photolists;
            $postdata = $this->getPostFileAndSave($postdata);
            $model->setAttributes($postdata);
            if ($model->validate() && $model->save()) {
                Yii::$app->getSession()->setFlash('success', '数据保存成功!');
            } else {
                Yii::$app->getSession()->setFlash('failed', '数据保存失败!');
            }
            return $this->redirect(['update', 'id'=>$model->Picorder->id]);
        }

        return $this->render('update',[
            'model'=>$model,
        ]);
    }


    /**
     * 处理用户上传数据
     * @param $postdata
     * @return mixed
     * @throws HttpException
     */
    private function getPostFileAndSave($postdata)
    {
        if (!empty($postdata)) {
            $picture = new Picture();
            $picfile = UploadedFile::getInstances($picture, 'picfile');
            if (!empty($picfile)) {
                //修改Photolists及Pictures中的数据
                foreach ($picfile as $k=>$fileeach) {
                    $saveFileDir = Yii::$app->PictureTool->generateFileSaveDir('org'.Yii::$app->user->id);
                    $picname = Yii::$app->PictureTool->generateFilename($fileeach->baseName, $fileeach->extension);
                    $filesavename = $saveFileDir . DIRECTORY_SEPARATOR . $picname;
                    $loaclsaveres = Yii::$app->PictureTool->saveUploadFileLocal($fileeach, $filesavename);
                    if ($loaclsaveres) {
                        $qiniusaveres = Yii::$app->PictureTool->saveUploadFileQiNiu($filesavename, $filesavename);
                        if ($loaclsaveres && $qiniusaveres) {
                            $postdata['Pictures']['new'.($k+1)] = array('picname'=>$picname, 'picdir'=>$saveFileDir,
                                'notes'=>isset($postdata['Picorder']['note'])?$postdata['Picorder']['note']:'add', 'picsavetype'=>Yii::getAlias('@picsavetype'));
                            $postdata['Photolists']['new'.($k+1)] = array('userid'=>Yii::$app->user->id);
                        } else {
                            throw new yii\web\HttpException(404, 'error create(1)!');
                        }
                    } else {
                        throw new yii\web\HttpException(404, 'error create(2)!');
                    }
                }
            }
        }
        return $postdata;
    }


}
