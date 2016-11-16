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
        <?= $form->field($model->Picorder, 'istodaysee')->textInput() ?>
        <?= $form->field($model->Picorder, 'ordertype')->textInput() ?>
        <?= $form->field($model->Picorder, 'orderstatus')->textInput() ?>
        <?= $form->field($model->Picorder, 'orderpiccount')->textInput() ?>
    </fieldset>

    <fieldset>
        <legend>上传照片
        </legend>
        <?php
        // upload table
        $picture = new Picture();
        $picture->loadDefaultValues();
        echo $this->render('_form-picture', [
            'form' => $form,
            'picture' => $picture,
        ]);
        ?>
    </fieldset>

    <?= Html::submitButton('Save'); ?>
    <?php ActiveForm::end(); ?>

</div>