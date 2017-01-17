<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\LinkPager;
use \yii\helpers\Markdown;
use \yii\helpers\Url;
use common\models\Picorder;

$this->title = '处理中的订单';
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
                        <th>订单状态</th>
                        <th>接单时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($operatingdata as $val) :?>
                        <tr>
                            <td><?=$val['orderid'] ?></td>
                            <td><?=Picorder::orderStatus($val['orderstatus']) ?></td>
                            <td><?=date('Y-m-d H:i:s', $val['time']) ?></td>
                            <td><?=$val['orderstatus']==Picorder::OS_ORDER_RETOUCHING ? Html::a('修片', ['repairphoto/create', 'id'=>$val['orderid']]) : Html::a('看片', ['/viewphoto/create', 'id'=>$val['orderid']]) ?></td>
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
