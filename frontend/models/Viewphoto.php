<?php
/**
 * Created by PhpStorm.
 * User: wangjstu
 * Date: 2016/10/15
 * Time: 15:44
 * 看片师model
 */

namespace frontend\models;

use yii;
use yii\base\Model;
use common\models\Retouchlist;
use common\models\Picorder;
use common\models\Picture;

class Viewphoto extends Model
{
    protected $_picorder; //一个表单应该只有一个订单记录
    protected $_retouchlists; //一个表单应该有多条(>0)拍摄记录，一个订单有多张照片，一张照片对应一条拍摄记录
    protected $_viewpictures; //一个表单应该有多条(>0)照片，一个订单有多张照片,看片师修片记录
    protected $_pictures; //原片
    protected $_repairpictures; //修片

    protected $picturesKeep; //记录picture对象插入后的id
    protected $orgPicturesKeep; //记录picture对象插入后的id

    public function rules()
    {
        return [
            [['Picorder'], 'required'],
            [['Pictures', 'Repairpictures','Retouchlists', 'Viewpictures'], 'safe']
        ];
    }

    /**
     * 验证所有model
     */
    public function afterValidate()
    {
        if (!Model::validateMultiple($this->getAllModels())) {
            $this->addError(null);
        }
        parent::afterValidate();
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        if (!$this->Picorder->save()) { //保存订单
            $transaction->rollBack();
            return false;
        }
        if (!$this->saveViewpictures()) {
            $transaction->rollBack();
            return false;
        }
        if (!$this->saveRetouchlists()) {
            $transaction->rollBack();
            return false;
        }
        $transaction->commit();
        Yii::$app->ServiceSupport->clearLock($this->Picorder->id, Yii::$app->user->id, Picorder::OS_ORDER_VIEWING);
        return true;
    }

    public function saveViewpictures()
    {
        $this->picturesKeep = [];
        $orgkeeptmp = [];
        foreach ($this->_pictures as $orgpic) {
            $orgkeeptmp[$orgpic->id] = Yii::$app->PictureTool->getOrgFilename($orgpic->picname);
        }
        foreach ($this->Viewpictures as $pid=>$viewpicture) {
            if (!$viewpicture->save(false)) {
                return false;
            }
            $this->orgPicturesKeep[$pid] = array_search(Yii::$app->PictureTool->getOrgFilename($viewpicture->picname), $orgkeeptmp, true);
            if($this->orgPicturesKeep[$pid] === false){
                $this->orgPicturesKeep[$pid] = 0;
            }
            $this->picturesKeep[$pid] = $viewpicture->id;
        }
        return true;
    }


    public function saveRetouchlists()
    {
        foreach ($this->Retouchlists as $pid=>$retouchlist) {
            $retouchlist->orderid = $this->Picorder->id;
            $retouchlist->opttype = Retouchlist::RETOUCHLIST_KANPIAN;
            $retouchlist->picid = isset($this->picturesKeep[$pid])?$this->picturesKeep[$pid]:0;
            $retouchlist->orgpicid = isset($this->orgPicturesKeep[$pid]) ? $this->orgPicturesKeep[$pid] : 0 ;
            if (!$retouchlist->save(false)) {
                return false;
            }
        }
        return true;
    }

    public function getPicorder()
    {
        return $this->_picorder;
    }

    public function setPicorder($picorder)
    {
        if ($picorder instanceof Picorder) {
            $this->_picorder = $picorder;
        } elseif (is_array($picorder)) {
            $this->_picorder->setAttributes($picorder);
        }
    }

    public function getViewpictures()
    {
        if ($this->_viewpictures === null) {
            $this->_viewpictures = $this->Picorder->isNewRecord ? [] : $this->Picorder->viewpictures;
        }
        return $this->_viewpictures;
    }

    public function getPictures()
    {
        if ($this->_pictures === null) {
            $this->_pictures = $this->Picorder->isNewRecord ? [] : $this->Picorder->pictures;
        }
        return $this->_pictures;
    }


    public function getRepairpictures()
    {
        if ($this->_repairpictures === null) {
            $this->_repairpictures = $this->Picorder->isNewRecord ? [] : $this->Picorder->retouchpictures;
        }
        return $this->_repairpictures;
    }

    public function getViewpicture($key)
    {
        $picture = $key && strpos($key, 'new') === false ? Picture::findOne($key) :false;
        if (!$picture) {
            $picture = new Picture();
            $picture->loadDefaultValues();
        }
        return $picture;
    }

    public function setViewpictures($pictures)
    {
        $this->_viewpictures = [];
        foreach ($pictures as $key=>$picture) {
            if (is_array($picture)) {
                $this->_viewpictures[$key] = $this->getViewpicture($key);
                $this->_viewpictures[$key]->setAttributes($picture);
            } elseif ($picture instanceof Picture) {
                $this->_viewpictures[$picture->id] = $picture;
            }
        }
    }

    public function getRetouchlists()
    {
        if ($this->_retouchlists === null) {
            $this->_retouchlists = $this->Picorder->isNewRecord ? [] : $this->Picorder->viewphotolists;
        }
        return $this->_retouchlists;
    }

    public function getRetouchlist($key)
    {
        $retouchlist = $key && strpos($key, 'new') === false ? Retouchlist::findOne($key) :false;
        if (!$retouchlist) {
            $retouchlist = new Retouchlist();
            $retouchlist->setScenario('kanpian');
            $retouchlist->loadDefaultValues();
        }
        return $retouchlist;
    }

    public function setRetouchlists($retouchlists)
    {
        $this->_retouchlists = [];
        foreach ($retouchlists as $key=>$retouchlist) {
            if (is_array($retouchlist)) {
                $this->_retouchlists[$key] = $this->getRetouchlist($key);
                $this->_retouchlists[$key]->setAttributes($retouchlist);
            } elseif ($retouchlist instanceof Retouchlist) {
                $this->_retouchlists[$retouchlist->id] = $retouchlist;
            }
        }
    }

    public function errorSummary($form)
    {
        $errorLists = [];
        foreach ($this->getAllModels() as $id => $model) {
            $errorList = $form->errorSummary($model, [
                'header' => '<p>Please fix the following errors for <b>' . $id . '</b></p>',
            ]);
            $errorList = str_replace('<li></li>', '', $errorList); // remove the empty error
            $errorLists[] = $errorList;
        }
        return implode('', $errorLists);
    }

    /**
     * 获取所有model class
     * @return array
     */
    public function getAllModels()
    {
        $models = [
            'Picorder' => $this->Picorder,
        ];
        if (!empty($this->Pictures)) {
            foreach ($this->Pictures as $pid=>$picture) {
                $models['Picture.' . $pid] = $picture;
            }
        }
        if (!empty($this->Repairpictures)) {
            foreach ($this->Repairpictures as $pid=>$picture) {
                $models['Picture.' . $pid] = $picture;
            }
        }
        if (!empty($this->Viewpictures)) {
            foreach ($this->Viewpictures as $pid=>$picture) {
                $models['Picture.' . $pid] = $picture;
            }
        }
        if (!empty($this->Retouchlists)) {
            foreach ($this->Retouchlists as $lid=>$retouchlist) {
                $models['Retouchlist.' . $lid] = $retouchlist;
            }
        }
        return $models;
    }

}