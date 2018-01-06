<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>编辑个人信息</title>
  <script src="/Public/lib/flexible/flexible_css.js"></script>
  <script src="/Public/lib/flexible/flexible.js"></script>
  <link rel="stylesheet" href="/Public/css/base.css">
  <link rel="stylesheet" href="/Public/css/common.css">
  <link rel="stylesheet" href="/Public/css/edit-info.css">
  <script src="/Public/js/jquery-3.1.0.min.js"></script>
  <script src="/Public/lib/fastclick/fastclick.min.js"></script>
</head>

<body>
  <!-- 头部 -->
  <header id="header">
    <a class="h-back">
      <img id="btn-back" src="/Public/static/icons/icon-back.png" alt="返回"> 返回
    </a>
    <span class="h-title">当贝通话</span>
  </header>
  <!-- 编辑个人信息 -->
  <section id="edit-info">
    <!-- 编辑头像 -->
    <div class="avatar">
      <div class="a-box">
        <img src="<?php echo $head_portrait?>" alt="头像">
      </div>
      <p class="txt">点击修改</p>
    </div>
    <!-- 表单 -->
    <form id="form" action="" method="post">
      <input id="codename" type="text" placeholder="请输入名称">
      <img id="btn-clear" src="/Public/static/icons/icon-delete.png" alt="删除">
      <input id="img-src" type="hidden" value="">
    </form>
    <!-- 确定按钮 -->
    <a class="btn-confirm" href="javascript:void(0);">确定</a>
  </section>
  <!-- 遮罩层-头像选项 -->
  <section id="avatar-tab" class="css">
    <div class="box">
      <div class="a-system">选择系统头像</div>
      <div class="a-album">
        在手机相册中选择头像
        <input type="file" name="file" accept="image/*" >
      </div>
      <div class="btn-cancel">取消</div>
    </div>
  </section>
</body>

<!-- 引入js文件 -->
<script src="/Public/js/common.js"></script>
<script src="/Public/js/edit-info.js"></script>

</html>