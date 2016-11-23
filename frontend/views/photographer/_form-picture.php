<?php
/**
 * 多图上传
 */
use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

echo $form->field($picture, 'picfile[]')->label('照片')->widget(FileInput::classname(),  [
    'options' => ['multiple' => true, 'accept' => 'image/*'],
    /*'id' => "Upload_{$key}_file",
    'name' => "Upload[$key][file]",*/
    'pluginOptions' => [
        // 需要预览的文件格式
        'previewFileType' => 'image',
        // 是否展示预览图
        'initialPreviewAsData' => true,
        // 最少上传的文件个数限制
        'minFileCount' => $isupdate?0:1, //更新限制0，新增至少上传1
        // 最多上传的文件个数限制
        'maxFileCount' => 20,
        // 是否显示移除按钮，指input上面的移除按钮，非具体图片上的移除按钮
        'showRemove' => true,
        // 是否显示上传按钮，指input上面的上传按钮，非具体图片上的上传按钮
        'showUpload' => false,
        //是否显示[选择]按钮,指input上面的[选择]按钮,非具体图片上的上传按钮
        'showBrowse' => true,
        // 展示图片区域是否可点击选择多文件
        'browseOnZoneClick' => true,
        // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
        'fileActionSettings' => [
            // 设置具体图片的查看属性为false,默认为true
            'showZoom' => false,
            // 设置具体图片的上传属性为true,默认为true
            'showUpload' => false,
            // 设置具体图片的移除属性为true,默认为true
            'showRemove' => true,
        ],
        'overwriteInitial' => false,
        'allowedFileExtensions'=>['jpg','gif','png'],
    ],
    // 一些事件行为
    'pluginEvents' => [
        // 上传成功后的回调方法，需要的可查看data后再做具体操作，一般不需要设置
        "fileuploaded" => "function (event, data, id, index) {
            console.log(data);
        }",
        //错误的冗余机制
        'error' => "function (){
            alert('图片上传失败');
        }"
    ],
]);