<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title></title>
    <!-- Bootstrap -->
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="/Public/css/main.css" rel="stylesheet">
  </head>
  <body>
    <header class="navbar" id="top">
      <div class="container">
        <div class="navbar-header">
          <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-navbar" aria-controls="bs-navbar" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          </button>
          <a href="../" class="navbar-brand">
            <img src="/Public/images/logo.png" height="48" width="135" >
          </a>
        </div>
        <nav id="bs-navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
              <li><a href="http://<?php echo C('HOST_HOME');?>" >首页</a></li>
              <li><a href="http://<?php echo C('HOST_HOME');?>#log" >交易记录</a></li>
          </ul>
        </nav>
      </div>
    </header>
    <div class="container-fluid darkbg">
      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-sm-offset-2 col-md-offset-2 col-lg-offset-2 bgw mt80 mb60 pt60 pt60">
            <div class="text-center" style="margin-top:60px"><i class="fa fa-refresh fa-spin" style="font-size: 100px ;color:#ccc; margin-bottom:20vh margin-top:30vh"></i></div>
            <div class="text-center" style="margin-bottom: 20px;margin-top: 40px;"><?php echo ($msg); ?></div>
            <div class="text-center">
                <a href="http://<?php echo C('HOST_HOME');?>" class="btn btn-danger" style="margin-bottom:25vh ">返回首页</a>
            </div>
           
            </div>
          </div>
         
        </div>
      </div>
      <footer id="footer">
        <div class="container-fluid text-center">
          <p class="footpad">Copyright © BTC 2017. All Rights Reserved</p>
        </div>
      </footer>
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="/Public/js/jquery.min.js"></script>
      <!-- Include all compiled plugins (below), or include individual files as needed -->
      <script src="/Public/js/bootstrap.min.js"></script>
    </body>
  </html>