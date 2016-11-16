<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

//文件上传相关
Yii::setAlias('@uploadedfilesdir', dirname(dirname(__DIR__)) . '/upload');
Yii::setAlias('@uploadmaxSize', 10); //10M
Yii::setAlias('@uploadfileextensions', 'jpg, gif, png');
Yii::setAlias('@uploadfilemimeTypes', 'image/jpeg, image/gif, image/png');
Yii::setAlias('@picsavetype', 3);
