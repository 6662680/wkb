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
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 bgw mt80 mb60 pt60 pt60">
            <div class="col-sm-12">
              <div class="row text-center ">
                <h3 class="text-center mb60">订单详情</h3>
                <div class="col-sm-10 col-sm-offset-1 paylist">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td><b>订单编号</b></td>
                        <td class="type-info"><?php echo ($order_number); ?></td>
                      </tr>
                      <tr>
                        <td><b>交易类型</b></td>
                        <td class="type-info"><?php echo ($type); ?></td>
                      </tr>
                      <tr>
                        <td><b>交易金额</b></td>
                        <td class="type-info"><?php echo ($total_price); ?></td>
                      </tr>
                      <tr>
                        <td><b>名称</b></td>
                        <td class="type-info"><?php echo ($name); ?></td>
                      </tr>
                      <tr>
                        <td><b>付款或收款方式</b></td>
                        <td class="type-info"><?php echo ($amount); ?></td>
                      </tr>
                      <tr>
                        <td><b>收款账号</b></td>
                        <td class="type-info"><?php echo ($card); ?></td>
                      </tr>
                      <tr>
                        <td><b>订单创建时间</b></td>
                        <td class="type-info"><?php echo ($creation_time); ?></td>
                      </tr>
                      <tr>
                        <td><b>订单状态</b></td>
                        <td class="type-info"><?php echo ($status); ?></td>
                      </tr>
                      <!--<tr>-->
                        <!--<td><b>操作</b></td>-->
                        <!--<td class="type-info"><a href="" class="btn btn-default"></a></td>-->
                      <!--</tr>-->
                    </tbody>
                  </table>
                </div>
              </div>
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
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>