<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>选择系统头像</title>
  <script src="/Public/lib/flexible/flexible_css.js"></script>
  <script src="/Public/lib/flexible/flexible.js"></script>
  <link rel="stylesheet" href="/Public/css/base.css">
  <link rel="stylesheet" href="/Public/css/common.css">
  <link rel="stylesheet" href="/Public/css/select-avatar.css">
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
  <!-- 系统头像选择 -->
  <section id="select-avatar">
    <!-- 预览 -->
    <img class="preview" src="<?php echo C("AVATAR_URL")?>//Public/static/headportrait/icon_default.png" alt="头像">
    <!-- 文字 -->
    <p class="txt">
      <span class="line"></span>
      请您选择喜欢的头像
      <span class="line"></span>
    </p>
    <!-- 头像列表 -->
    <ul class="a-list">
      <li>
        <img src="/Public/static/headportrait/Faces_grandpa.png" alt="">
      </li>
      <li>
        <img src="/Public/static/headportrait/Faces_grandma.png" alt="">
      </li>
      <li>
        <img src="/Public/static/headportrait/Faces_dad.png" alt="">
      </li>
      <li>
        <img src="/Public/static/headportrait/Faces_mom.png" alt="">
      </li>
      <li>
        <img src="/Public/static/headportrait/Faces_uncle.png" alt="">
      </li>
      <li>
        <img src="/Public/static/headportrait/Faces_aunt.png" alt="">
      </li>
      <li>
        <img src="/Public/static/headportrait/Faces_belderbrother.png" alt="">
      </li>
      <li>
        <img src="/Public/static/headportrait/Faces_beldersister.png" alt="">
      </li>
      <li>
        <img src="/Public/static/headportrait/Faces_youngerbrother.png" alt="">
      </li>
      <li>
        <img src="/Public/static/headportrait/Faces_youngersister.png" alt="">
      </li>
    </ul>
    <!-- 确定按钮 -->
    <a class="btn-confirm" href="javascript:void(0);">确定</a>
  </section>
</body>

<!-- 引入js文件 -->
<script src="/Public/js/common.js"></script>
<script src="/Public/js/select-avatar.js"></script>

</html>