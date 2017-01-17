<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\LinkPager;
use \yii\helpers\Markdown;
use \yii\helpers\Url;
use common\models\Picorder;

$this->title = '接单';
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
                        <th>接单时间</th>
                        <th>订单状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=$infoData->orderid ?></td>
                            <td><?=date('Y-m-d H:i:s', $infoData->time) ?></td>
                            <td><?=Picorder::orderStatus($infoData->orderstatus) ?></td>
                            <td>操作</td>
                        </tr>
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
<?php
$this->registerJs(
    '$(function () {
        $("#historyordertable").DataTable({
          "paging": false,
          "lengthChange": false,
          "searching": false,
          "ordering": false,
          "info": false,
          "autoWidth": false
        });
    });'
);
?>
