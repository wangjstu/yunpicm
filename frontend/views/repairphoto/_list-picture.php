<?php
/**
 * Created by PhpStorm.
 * User: wangjstu
 * Date: 2016/11/16
 * Time: 21:28
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="row">
    <?php
    $thumbnailAttribute = array(
        'imageView2' => array(
            'w'=>171,
            'h'=>180
        )
    );
    foreach ($listpictures as $picid=>$picinfo) {
    ?>
    <div class="col-sm-6 col-md-3">
        <a href="#" class="thumbnail">
            <img src="<?=Yii::$app->PictureTool->getUploadFileQiNiuLink($picinfo['picdir'].DIRECTORY_SEPARATOR.$picinfo['picname'], $thumbnailAttribute);?>"
                 alt="正在加载照片...">
        </a>
    </div>
    <?php
    }
    ?>
</div>
