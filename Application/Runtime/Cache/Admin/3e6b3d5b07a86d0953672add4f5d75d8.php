<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>
        后台管理
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
    <i class="Hui-iconfont">&#xe67f;</i> 人物管理 <span class="c-gray en">&gt;</span> <a href="<?php echo U('Pconfig/index');?>">人物升级配置列表</a><span class="c-gray en">&gt;</span> <?php echo ($actionName); ?>
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新"> <i class="Hui-iconfont">&#xe68f;</i></a>
	   </nav>
    <div class="page-container">
        <form action="" method="post" class="form form-horizontal" id="form-member-add" novalidate="novalidate">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>等级阶段:</label>
                <div class="formControls col-xs-8 col-sm-3">
                    <input type="text" class="input-text" value="<?php echo ($config[level]); ?>"  disabled>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>实际等级跨度:</label>
                <div class="formControls col-xs-8 col-sm-3">
                    <input type="text" class="input-text" value="<?php echo ($config[tlevel]); ?>~<?php echo ($config[tlevel2]); ?>级"  disabled>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>难度时间:</label>
                <div class="formControls col-xs-8 col-sm-3">
                    <input type="text" class="input-text" value="<?php echo ($config[value]); ?>" name="nvalue" >
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <input type="hidden" name="level" value="<?php echo ($config[level]); ?>">
                    <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
                </div>
            </div>
        </form>
    </div>


</html>