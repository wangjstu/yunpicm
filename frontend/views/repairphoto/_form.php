<?php
use common\models\Picorder;
use common\models\Picture;
use common\models\Retouchlist;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Pubtype;
?>
<div class="product-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $model->errorSummary($form); ?>
    <fieldset>
        <legend>上传照片</legend>
        <?= $form->field($model->Picorder, 'originalid')->textInput(['readonly' => 'true']) ?>
        <?= $form->field($model->Picorder, 'notes')->textInput(['readonly' => 'true']) ?>
        <?= $form->field($model->Picorder, 'contacts')->textInput(['readonly' => 'true']) ?>
        <?= $form->field($model->Picorder, 'contacttel')->textInput(['readonly' => 'true']) ?>
        <?= $form->field($model->Picorder, 'istodaysee')->dropDownList(['否', '是'],['disabled' => 'true']) ?>
        <?= $form->field($model->Picorder, 'ordertype')->dropDownList(Pubtype::getOrderType(), ['disabled' => 'true']) ?>
        <?= $form->field($model->Picorder, 'orderstatus')->textInput(['readonly' => 'true'])->hiddenInput(['value'=>Picorder::OS_ORDER_READY_VIEW])->label(false) ?>
        <?= $form->field($model->Picorder, 'orderpiccount')->textInput(['readonly' => 'true']) ?>
    </fieldset>
    <?php
        if ( $this->context->action->id == 'update') {
    ?>
    <fieldset>
        <legend>已经完成照片</legend>
        <?php
        $listpictures = array();
        foreach ($model->Repairpictures as $picone) {
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
        <legend>提交修片结果</legend>
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