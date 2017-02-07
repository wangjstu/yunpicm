<?php
use common\models\Picorder;
use common\models\Picture;
use common\models\Retouchlist;
use common\models\Photolist;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Pubtype;

$this->title = '订单详情';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-form">
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <fieldset>
        <legend>订单详情</legend>
        <ul>
            <li>
                <label><?=Yii::t('app', 'Originalid')?> : </label> <?=$orderdata->originalid ?>
            </li>
            <li>
                <label><?=Yii::t('app', 'Notes')?> : </label> <?=$orderdata->notes ?>
            </li>
            <li>
                <label><?=Yii::t('app', 'Contacts')?> : </label> <?=$orderdata->contacts ?>
            </li>
            <li>
                <label><?=Yii::t('app', 'Istodaysee')?> : </label> <?=$orderdata->istodaysee?'是':'否' ?>
            </li>
            <li>
                <label><?=Yii::t('app', 'Ordertype')?> : </label> <?= Pubtype::getOrderType($orderdata->ordertype)?>
            </li>
            <li>
                <label><?=Yii::t('app', 'Orderstatus')?> : </label> <?=Picorder::orderStatus($orderdata->orderstatus) ?>
            </li>
            <li>
                <label><?=Yii::t('app', 'Orderpiccount')?> : </label> <?=$orderdata->orderpiccount ?>
            </li>
        </ul>
        </fieldset>
    <?php if($photodata): ?>
    <fieldset>
        <legend>拍摄照片</legend>
        <div class="row">
            <?php
            $thumbnailAttribute = array(
                'imageView2' => array(
                    'w'=>171,
                    'h'=>180
                )
            );
            foreach ($photodata as $picid=>$picinfo) {
                ?>
                <div class="col-sm-6 col-md-3">
                    <a href="<?=Yii::$app->PictureTool->getUploadFileQiNiuLink($picinfo->picdir.DIRECTORY_SEPARATOR.$picinfo->picname);?>" target="_blank" class="thumbnail">
                        <img src="<?=Yii::$app->PictureTool->getUploadFileQiNiuLink($picinfo->picdir.DIRECTORY_SEPARATOR.$picinfo->picname, $thumbnailAttribute);?>"
                             alt="正在加载照片...">
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </fieldset>
    <?php endif; ?>
    <?php if($retouchdata): ?>
    <fieldset>
        <legend>修片修理照片</legend>
        <div class="row">
            <?php
            $thumbnailAttribute = array(
                'imageView2' => array(
                    'w'=>171,
                    'h'=>180
                )
            );
            foreach ($retouchdata as $picid=>$picinfo) {
                ?>
                <div class="col-sm-6 col-md-3">
                    <a href="<?=Yii::$app->PictureTool->getUploadFileQiNiuLink($picinfo->picdir.DIRECTORY_SEPARATOR.$picinfo->picname);?>" target="_blank" class="thumbnail">
                        <img src="<?=Yii::$app->PictureTool->getUploadFileQiNiuLink($picinfo->picdir.DIRECTORY_SEPARATOR.$picinfo->picname, $thumbnailAttribute);?>"
                             alt="正在加载照片...">
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </fieldset>
    <?php endif; ?>
    <?php if($viewdata): ?>
    <fieldset>
        <legend>看片修理照片</legend>
        <div class="row">
            <?php
            $thumbnailAttribute = array(
                'imageView2' => array(
                    'w'=>171,
                    'h'=>180
                )
            );
            foreach ($viewdata as $picid=>$picinfo) {
                ?>
                <div class="col-sm-6 col-md-3">
                    <a href="<?=Yii::$app->PictureTool->getUploadFileQiNiuLink($picinfo->picdir.DIRECTORY_SEPARATOR.$picinfo->picname);?>" target="_blank" class="thumbnail">
                        <img src="<?=Yii::$app->PictureTool->getUploadFileQiNiuLink($picinfo->picdir.DIRECTORY_SEPARATOR.$picinfo->picname, $thumbnailAttribute);?>"
                             alt="正在加载照片...">
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </fieldset>
    <?php endif; ?>
</div>

