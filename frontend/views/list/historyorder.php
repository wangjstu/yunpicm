<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\LinkPager;
use \yii\helpers\Markdown;
use \yii\helpers\Url;
use common\models\Picorder;
use common\models\Retouchlist;

$this->title = $usertype.'历史订单';
//$this->params['breadcrumbs'][] = $this->title;
frontend\assets\DatatableAsset::register($this);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?=$this->title ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="historyordertable" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>订单ID</th>
                        <th>联系人姓名</th>
                        <th>联系人电话</th>
                        <th>是否当天看</th>
                        <th>照片总数</th>
                        <th>订单状态</th>
                        <th>订单创建时间</th>
                        <th>最近修改时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($models as $val) :?>
                        <tr>
                            <td><?=$val->id ?></td>
                            <td><?=$val->contacts ?></td>
                            <td><?=$val->contacttel ?></td>
                            <td><?=$val->istodaysee?'是':'否' ?></td>
                            <td><?=$val->orderpiccount ?></td>
                            <td><?=Picorder::printOrderStatus($val->id, $val->orderstatus) ?></td>
                            <td><?=date('Y-m-d H:i:s', $val->created_at) ?></td>
                            <td><?=date('Y-m-d H:i:s', $val->updated_at) ?></td>
                            <td><?= (Picorder::printOrderStatus($val->id, $val->orderstatus, true)==Picorder::OS_ORDER_READY_RETOUCH && $type==0) ? Html::a('修改', ['photographer/update', 'id'=>$val->id]) : ((Picorder::printOrderStatus($val->id, $val->orderstatus, true)==Picorder::OS_ORDER_READY_VIEW && $type==Retouchlist::RETOUCHLIST_XIUPIAN) ? Html::a('修改', ['repairphoto/update', 'id'=>$val->id]) : Html::a('查看', ['list/show-detail', 'orderid'=>$val->id])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<div class="row">
    <div class="col-sm-5"></div>
    <div class="col-sm-7">
        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            <?php

            // 显示分页
            echo LinkPager::widget([
                'pagination' => $pages,
                'nextPageLabel' => '下一页',
                'prevPageLabel' => '上一页',
                //'firstPageLabel' => '首页',
                //'lastPageLabel' => '尾页',
                'hideOnSinglePage' => false,
                'maxButtonCount' => 5,
                'options' => ['class' => 'pagination'],
            ]);
            ?>
        </div>
    </div>
</div>
<?php
$this->registerJs(
    '$(function () {
        $("#historyordertable").DataTable({
          "paging": false,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": false,
          "autoWidth": false
        });
    });'
);
?>
