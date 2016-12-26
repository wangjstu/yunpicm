<?php
namespace common\components;

use crazyfd\qiniu\Qiniu;
use yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\base\Exception;
/**
 * Created by PhpStorm.
 * User: wangjstu
 * Date: 2016/10/21
 * Time: 22:38
 * http://www.yiichina.com/tutorial/354
 */
class PictureTool extends Component
{
    /**
     * 生成文件保存路径及名称，确保云与本地名称可以对上
     * @param string $prefolder
     * @return string
     */
    public function generateFileSaveDir($prefolder='org')
    {
        $savePath = yii::getAlias('@uploadedfilesdir'). DIRECTORY_SEPARATOR. date('Ymd');
        $savePath.= DIRECTORY_SEPARATOR . $prefolder;
        if (!file_exists($savePath)) {
            mkdir($savePath, 0777, true);
        }
        return $savePath;
    }

    /**
     * 生成文件名称
     * @param $orgbasename
     * @param $orgextension
     * @return string
     */
    public function generateFilename($orgbasename, $orgextension)
    {
        return md5(Yii::$app->security->generateRandomString()).'_'.$orgbasename.'.'.$orgextension;
    }

    /**
     * 根据上面的generateFilename来获取文件的原来名称
     * @param $filename
     * @param string $delimiter
     * @return string
     */
    public function getOrgFilename($filename, $delimiter='_')
    {
        $strposdel = strpos($filename, $delimiter);
        $lastpot = strpos($filename, '.');
        if ($strposdel!==false && $lastpot!==false && $lastpot>$strposdel) {
            return substr($filename, $strposdel);
        } else {
            return $filename;
        }
    }

    /**
     * 将图片保存到本地
     * @param $uploadFileinstance
     * @param $fileSaveDirAndName
     * @return bool
     * @throws Exception
     */
    public function saveUploadFileLocal($uploadFileinstance, $fileSaveDirAndName)
    {
        if ($uploadFileinstance instanceof UploadedFile && !empty($fileSaveDirAndName)) {
            return $uploadFileinstance->saveAs($fileSaveDirAndName);
        } else {
            throw new Exception('Save Upload File Local only support UploadedFile instance. '.__FILE__);
        }
    }


    /**
     * 将图片保存到七牛
     * @param $localfileDirAndName
     * @param $fileSaveDirAndName
     * @return array|mixed
     * @throws Exception
     */
    public function saveUploadFileQiNiu($localfileDirAndName, $fileSaveDirAndName, $isNeedLink = true)
    {
        if (file_exists($localfileDirAndName) && !empty($fileSaveDirAndName)) {
            $qiNiuTool = $this->getQiniuTool();
            $res = $qiNiuTool->uploadFile($localfileDirAndName, $fileSaveDirAndName);
            if ($isNeedLink) {
                $res['reslink'] = $qiNiuTool->getLink($fileSaveDirAndName);
            }
            return $res;
        } else {
            throw new Exception('Save Upload File QINIU only support UploadedFile instance. '.__FILE__);
        }
    }

    /**
     * 获取已经上传的照片
     * @param $fileSaveDirAndName
     * @param array $attribute 支持：缩略图(imageView2)
     * @return string
     * @throws Exception
     */
    public function getUploadFileQiNiuLink($fileSaveDirAndName, $attribute=array())
    {
        if (!empty($fileSaveDirAndName)) {
            $qiNiuTool = $this->getQiniuTool();
            return $qiNiuTool->getAuthLink($fileSaveDirAndName, $attribute);
        } else {
            throw new Exception('Save Upload File QINIU only support UploadedFile instance. '.__FILE__);
        }
    }

    public function getQiniuTool()
    {
        $ak = Yii::$app->params['qiniu_AccessKey'];
        $sk = Yii::$app->params['qiniu_SecretKey'];
        $domain = Yii::$app->params['qiniu_domain'];
        $bucket = Yii::$app->params['qiniu_bucket'];
        $qiNiuTool = new Qiniu($ak, $sk, $domain, $bucket);
        return $qiNiuTool;
    }
}