<?php
/**
 * Created by PhpStorm.
 * User: wangjstu
 * Date: 2016/10/15
 * Time: 15:36
 * 摄影师model->{photolist,picorder,picture}
 */

namespace frontend\models;

use yii;
use yii\base\Model;
use common\models\Picorder;
use common\models\Photolist;
use common\models\Picture;
use yii\web\HttpException;

class Photographer extends Model
{
    protected $_picorder; //一个表单应该只有一个订单记录
    protected $_photolists; //一个表单应该有多条(>0)拍摄记录，一个订单有多张照片，一张照片对应一条拍摄记录
    protected $_pictures; //一个表单应该有多条(>0)照片，一个订单有多张照片

    protected $picturesKeep; //记录picture对象插入后的id

    public function rules()
    {
        return [
            [['Picorder','Pictures','Photolists'], 'required'],
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
        if (!$this->savePictures()) {
            $transaction->rollBack();
            return false;
        }
        if (!$this->savePhotolists()) {
            $transaction->rollBack();
            return false;
        }
        $transaction->commit();
        return true;
    }

    public function savePictures()
    {
        $this->picturesKeep = [];
        foreach ($this->Pictures as $pid=>$picture) {
            if (!$picture->save(false)) {
                return false;
            }
            $this->picturesKeep[$pid] = $picture->id;
        }
        return true;
    }


    public function savePhotolists()
    {
        foreach ($this->Photolists as $pid=>$photolist) {
            $photolist->orderid = $this->Picorder->id;
            if (isset($this->picturesKeep[$pid])) $photolist->picid = $this->picturesKeep[$pid];
            if (!$photolist->save(false)) {
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

    public function getPictures()
    {
        if ($this->_pictures === null) {
            $this->_pictures = $this->Picorder->isNewRecord ? [] : $this->Picorder->pictures;
        }
        return $this->_pictures;
    }

    public function getPicture($key)
    {
        $picture = $key && strpos($key, 'new') === false ? Picture::findOne($key) :false;
        if (!$picture) {
            $picture = new Picture();
            $picture->loadDefaultValues();
        }
        return $picture;
    }

    public function setPictures($pictures)
    {
        $this->_pictures = [];
        foreach ($pictures as $key=>$picture) {
            if (is_array($picture)) {
                $this->_pictures[$key] = $this->getPicture($key);
                $this->_pictures[$key]->setAttributes($picture);
            } elseif ($picture instanceof Picture) {
                $this->_pictures[$picture->id] = $picture;
            }
        }
    }

    public function getPhotolists()
    {
        if ($this->_photolists === null) {
            $this->_photolists = $this->Picorder->isNewRecord ? [] : $this->Picorder->photolists;
        }
        return $this->_photolists;
    }

    public function getPhotolist($key)
    {
        $photolist = $key && strpos($key, 'new') === false ? Photolist::findOne($key) :false;
        if (!$photolist) {
            $photolist = new Photolist();
            $photolist->loadDefaultValues();
        }
        return $photolist;
    }

    public function setPhotolists($photolists)
    {
        $this->_photolists = [];
        foreach ($photolists as $key=>$photolist) {
            if (is_array($photolist)) {
                $this->_photolists[$key] = $this->getPhotolist($key);
                $this->_photolists[$key]->setAttributes($photolist);
            } elseif ($photolist instanceof Photolist) {
                $this->_photolists[$photolist->id] = $photolist;
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
        if (!empty($this->Photolists)) {
            foreach ($this->Photolists as $lid=>$photolist) {
                $models['Photolist.' . $lid] = $photolist;
            }
        }
        return $models;
    }

}