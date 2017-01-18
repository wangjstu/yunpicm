<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '修改密码';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">


    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
            <table>
                <tr>
                    <td><label>新密码： </label></td>
                    <td><input type="password" maxlength="18" name="newpassword1"></li></td>
                </tr>
                <tr>
                    <td><label>重复新密码： </label></td>
                    <td><input type="password" maxlength="18" name="newpassword2"></li></td>
                </tr>
            </table>
            <div class="form-group">
                <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
