<?php
use yii\helpers\Url;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->identity->username; ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <!--<form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->

        <ul class="sidebar-menu">
            <li class="header"><span>菜单</span></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-lock"></i> 用户管理 <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo Url::to(['/site/signup']);?>"><i class="fa fa-circle-o"></i> 新建用户</a></li>
                    <li><a href="<?php echo Url::to(['site/reset-password-by-password']);?>"><i class="fa fa-circle-o"></i> 修改密码</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-lock"></i> 权限控制 <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo Url::to(['/admin/route']);?>"><i class="fa fa-circle-o"></i> 路由</a></li>
                    <li><a href="<?php echo Url::to(['/admin/permission']);?>"><i class="fa fa-circle-o"></i> 权限</a></li>
                    <li><a href="<?php echo Url::to(['/admin/role']);?>"><i class="fa fa-circle-o"></i> 角色</a></li>
                    <li><a href="<?php echo Url::to(['/admin/assignment']);?>"><i class="fa fa-circle-o"></i> 分配</a></li>
                    <li><a href="<?php echo Url::to(['/admin/menu']);?>"><i class="fa fa-circle-o"></i> 菜单</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file-video-o"></i> 摄影师 <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo Url::to(['/photographer/create']);?>"><i class="fa fa-circle-o"></i> 上传照片</a></li>
                    <li><a href="<?php echo Url::to(['/list/history-order', 'type'=>0]);?>"><i class="fa fa-circle-o"></i> 历史订单</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa  fa-pencil-square"></i> 修片师 <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo Url::to(['/list/ready-order', 'status'=>1]);?>"><i class="fa fa-circle-o"></i> 待接订单</a></li>
                    <li><a href="<?php echo Url::to(['/list/operating']);?>"><i class="fa fa-circle-o"></i> 处理订单</a></li>
                    <li><a href="<?php echo Url::to(['/list/history-order', 'type'=>1]);?>"><i class="fa fa-circle-o"></i> 历史订单</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-photo"></i> 看片师 <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo Url::to(['/list/ready-order', 'status'=>3]);?>"><i class="fa fa-circle-o"></i> 待接订单</a></li>
                    <li><a href="<?php echo Url::to(['/list/operating']);?>"><i class="fa fa-circle-o"></i> 处理订单</a></li>
                    <li><a href="<?php echo Url::to(['/list/history-order', 'type'=>2]);?>"><i class="fa fa-circle-o"></i> 历史订单</a></li>
                </ul>
            </li>
        </ul>
        <?php
        use mdm\admin\components\MenuHelper;
        $callback = function($menu){
            $data = json_decode($menu['data'], true);
            $items = $menu['children'];
            $return = ['label' => $menu['name'],'url' => [$menu['route']]];
            //处理我们的配置
            if ($data) {
                isset($data['visible']) && $return['visible'] = $data['visible'];//visible
                isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon'];//icon
                //other attribute e.g. class...
                $return['options'] = $data;
            }
            //没配置图标的显示默认图标
            (!isset($return['icon']) || !$return['icon']) && $return['icon'] = 'fa fa-circle-o';
            $items && $return['items'] = $items;
            return $return;
        };
        //这里我们对一开始写的菜单menu进行了优化
        echo dmstr\widgets\Menu::widget( [
            'options' => ['class' => 'sidebar-menu'],
            'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id,null, $callback),
        ] );
        ?>

    </section>

</aside>