<?php
use common\models\Picorder;
use common\models\Picture;
use common\models\Photolist;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="product-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $model->errorSummary($form); ?>
    <fieldset>
        <legend>上传照片</legend>
        <?= $form->field($model->Picorder, 'originalid')->textInput() ?>
        <?= $form->field($model->Picorder, 'notes')->textInput() ?>
        <?= $form->field($model->Picorder, 'contacts')->textInput() ?>
        <?= $form->field($model->Picorder, 'contacttel')->textInput() ?>
        <?= $form->field($model->Picorder, 'istodaysee')->dropDownList(['否', '是']) ?>
        <?= $form->field($model->Picorder, 'ordertype')->textInput() ?>
        <?= $form->field($model->Picorder, 'orderstatus')->textInput()->hiddenInput(['value'=>Picorder::OS_ORDER_READY_RETOUCH])->label(false) ?>
        <?= $form->field($model->Picorder, 'orderpiccount')->textInput() ?>
    </fieldset>
    <?php
        if ( $this->context->action->id == 'update') {
    ?>
    <fieldset>
        <legend>已经上传照片</legend>
        <?php
        $listpictures = array();
        foreach ($model->Pictures as $picone) {
            $listpictures[$picone->attributes['id']] = $picone->attributes;
        }
        echo $this->render('_list-picture', [
            'form' => $form,
            'listpictures' => $listpictures,
        ]);
        ?>
    </fieldset>
    <?php
        }
    ?>
    <fieldset>
        <legend>增加照片</legend>
        <?php
        // upload table
        $picture = new Picture();
        $picture->loadDefaultValues();
        echo $this->render('_form-picture', [
            'form' => $form,
            'picture' => $picture,
            'isupdate' => ($this->context->action->id == 'update')
        ]);
        ?>
    </fieldset>

    <?= Html::submitButton('保存'); ?>
    <?php ActiveForm::end(); ?>

</div>