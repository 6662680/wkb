<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>
        后台人员管理
    </title>
    <link rel="stylesheet" type="text/css" href="/Public/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/Public/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/Public/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="/Public/static/h-ui.admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/Public/lib/Hui-iconfont/1.0.7/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/Public/lib/icheck/icheck.css" />

    
    <script type="text/javascript" src="/Public/lib/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.icheck.min.js"></script>

    <script type="text/javascript" src="/Public/lib/layer/2.1/layer.js"></script>
    <script type="text/javascript" src="/Public/static/h-ui/js/H-ui.js"></script>
    <script type="text/javascript" src="/Public/static/h-ui.admin/js/H-ui.admin.js"></script>
    <script type="text/javascript" src="/Public/laydate/laydate.dev.js"></script>
    <script type="text/javascript" src="/Public/lib/layer/2.1/layer.js"></script>
    <script type="text/javascript" src="/Public/lib/laypage/1.2/laypage.js"></script>
    <link rel="stylesheet" type="text/css" href="/Public/ichartjs1.2/samples/css/demo.css" />
    <script type="text/javascript" src="/Public/ichartjs1.2/ichart.1.2.min.js"></script> 
    
</head>


    <nav class="breadcrumb">
    <i class="Hui-iconfont">&#xe67f;</i> 后台人员管理 <span class="c-gray en">&gt;</span> 食物管理<span class="c-gray en">&gt;</span> 食物背包列表
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新"> <i class="Hui-iconfont">&#xe68f;</i></a>
	</nav>
    <div class="page-container">
        <form class="form-inline definewidth m20" id="searchForm" action="">
        </form>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
        	<span class="l"> 
        		<aa class="btn btn-primary radius" >食物背包列表</aa>
        	</span>
        </div>
            <br>
        <table class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr>
                <th>ID</th>
                <th>所属会员ID</th>
                <th>食物名称</th>
                <th>食物数量</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($mediche_bagList)): foreach($mediche_bagList as $key=>$vo): ?><tr >
                   <td><?php echo ($vo["id"]); ?></td>
                   <td><?php echo ($vo["user_id"]); ?></td>
                   <td>
                   	<?php  if ($vo['mediche_id']==1) { echo '牛奶'; } elseif($vo['mediche_id']==2) { echo '面包'; }elseif($vo['mediche_id']==3) { echo '蛋糕'; }elseif($vo['mediche_id']==4) { echo '米饭套餐'; } ?>
                   </td>
                   <td><?php echo ($vo["mediche_num"]); ?></td>
                </tr><?php endforeach; endif; ?>
            </tbody>
        </table>
        <div class="pager">
            <?php echo ($page); ?>
        </div>
    </div>



</html>