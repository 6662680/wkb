<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>裁剪</title>
  <script src="/Public/lib/flexible/flexible_css.js"></script>
  <script src="/Public/lib/flexible/flexible.js"></script>
  <link rel="stylesheet" href="/Public/css/base.css">
  <link rel="stylesheet" href="/Public/css/cropper.css">
  <script src="/Public/js/jquery-3.1.0.min.js"></script>
  <script src="/Public/lib/fastclick/fastclick.min.js"></script>
</head>

<body>
  <!-- 头部 -->
  <header id="header">
    <img id="btn-back" src="/Public/static/icons/icon-back.png" alt="返回">
    <span class="txt">移动和缩放</span>
  </header>
  <!-- 裁剪图片区域 -->
  <section id="cropper">
    <p class="txt">请点击图片进行裁剪</p>
    <img src="" alt="">
    <div class="pre-box"></div>
  </section>
  <!-- 确定按钮 -->
  <section id="complete">
    <a id="btn-complete" href="javascript:void(0);">完成</a>
  </section>
</body>

<!-- 引入js文件 -->
<script src="/Public/js/common.js"></script>
<script src="/Public/lib/alloycrop/transform.js"></script>
<script src="/Public/lib/alloycrop/alloy-finger.js"></script>
<script src="/Public/lib/alloycrop/alloy-crop.js"></script>
<script src="/Public/js/cropper.js"></script>

</html>