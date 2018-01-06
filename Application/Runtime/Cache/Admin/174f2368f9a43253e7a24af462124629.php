<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>
        查看用户
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

    
<div class="cl pd-20" style=" background-color:#5bacb6">

    <img class="avatar size-XL l" src="<?php echo C('AVATAR_URL').$merchandise['img']?>">

  <dl style="margin-left:80px; color:#fff">
    <dt><span class="f-18"> </span> <span class="pl-10 f-12"> </span></dt>
    <dd class="pt-10 f-12" style="margin-left:0">
        <?php if(!empty($user["sign"])): echo ($user["sign"]); ?>
        <?php else: ?>
            <!--这家伙很懒，什么也没有留下--><?php endif; ?>        
    </dd>
  </dl>
</div>
<div class="pd-20">
  <table class="table">
    <tbody>
    <tr>
        <th class="text-r">创建时间：</th>
        <td><?php echo (date("Y-m-d H:i:s",$merchandise["create_time"])); ?></td>
    </tr>
    <tr>
        <th class="text-r">简介：</th>
        <td><?php echo $merchandise['introduce']?></td>
    </tr>

    <tr>
        <th class="text-r">详情图：</th>
        <td><img src="<?php echo C('AVATAR_URL').$merchandise['detail_img']?>"></td>
    </tr>

    </tbody>
  </table>
</div>
    


</html>