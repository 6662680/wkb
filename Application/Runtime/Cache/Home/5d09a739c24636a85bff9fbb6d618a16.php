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
    <link href="/Public/css/main.css?v=1" rel="stylesheet">
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
            <div class="col-sm-10 col-sm-offset-1">
              <div class="in-operator-header">
                <div class="operator-logo-container">
                  <div class="operator-logo">
                    <img  class="biticon" src="/Public/images/btcicon.jpg"  >
                  </div>
                </div>
                <h2>订单金额 <span class="redprice"><?php echo ($total); ?></span> 元</h2>
                <table class="table  table-bordered">
                  <thead>
                    <tr >
                      <th class="text-center">交易数量</th>
                      <th class="text-center">类型</th>
                      <th class="text-center">成交价格</th>
                      <th class="text-center">交易额</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                    <td  class="text-center"><?php echo ($num); ?> BTC</th>
                    <td class="text-center"><span class="label label-danger"><?php echo ($type); ?></span></td>
                    <td class="text-center"><?php echo ($price); ?></td>
                    <td class="text-center"><?php echo ($total); ?></td>
                  </tr>
                </tbody>
              </table>

            </div>
          </div>
          <div class="col-sm-10 col-sm-offset-1">
            <h3 class="mb20 text-center">请核对你的信息</h3>
              <p>您的姓名：<?php echo ($name); ?></p>
              <p>您的<?php echo ($amount); ?>号：<?php echo ($card); ?></p>
              <p class="input-hint alerta">*若信息有误,请返回首页重新下单.</p>
              <hr class="hrpa">
            </div>
            <div class="col-sm-10 col-sm-offset-1">
              <h3 class="mb20 text-center">请核对你的付款方式</h3>
              <p class="text-center f30">
                <?php echo ($amount); ?>
              </p>
              <hr class="hrpa">
            </div>

            <div class="col-sm-10 col-sm-offset-1">
              <h3 class="mb20 text-center">请核对你的出售数额</h3>
              <p class="text-center f30">
                <span class="sellnum"><?php echo ($num); ?></span>BTC
              </p>
              <hr class="hrpa">
            </div>
            <div class="col-sm-10 col-sm-offset-1">
              <h3 class="mb20 text-center">请核对你的交易金额</h3>
              <p class="text-center f30">
                人民币<span class="sellnum"><?php echo ($total); ?></span>元
              </p>
              <hr class="hrpa">
            </div>
            

            <div class="col-sm-10 col-sm-offset-1 mb60">
              <h3 class="mb20 text-center">请在下方填入验证码</h3>
              <form action=<?php echo U('Index/remit');?> method="post" id="subfrom" style="text-align: center;">
                <div class=""></div>
                  <div class="" style="display: inline-block;    margin: 20px 0;">
                      <div class="clickcode">
                        <input type="text" id="clickcode" class="form-control" name="code" placeholder="请输入验证码">
                      </div>
                      <div class="addonon right success"><i class="fa fa-check-circle "></i></div>
                      <div class="addonon wrong waring"><i class="fa fa-times-circle "></i></div>
                      <div class="input-group-btn pull-left" style=" margin-left: 10px;">
                        <img src="<?php echo U('Index/vcode');?>"  onclick="this.src='/Home/Index/vcode.html?k='+Math.random()"/>
                      </div>
                      <div class="clearfix"></div>
                  </div>
                  <div class="row">
                    <hr class="hrpa">
                    <input type="hidden" class="form-control" name="token" value="<?php echo ($token); ?>">
                    <div class="col-sm-6"> <a href="index.html" class="btn btn-block btn-default mt20">取消</a></div>
                    <div class="col-sm-6"> <button type="submit" class="btn btn-block btn-danger mt20" id="submitbtn" disabled="disabled">确定</button></div>
                  </div>
                </form>
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
      <script src="//captcha.luosimao.com/static/js/api.js"></script>
      <script type="text/javascript">
      $("#clickcode").on('input', function() {

         $('.right').attr("disabled","false")
        var clickcode = $("#clickcode").val().length;
            $('.wrong').removeClass('show');
            $('.right').removeClass('show');
            $('#submitbtn').attr("disabled",true);
//            $('#submitbtn').on("click",function(){
//               $('#submitbtn').attr("disabled",true);
//            })
        if (clickcode == '4') {
          $.ajax({
            type: "post",
            url: "<?php echo U('Index/checkVcode');?>",//这里填写url
            data: {
              code: $("#clickcode").val(),
            },
            success: function(data) {
              if (data) {

                $('.right').addClass('show');
                $('.wrong').removeClass('show');
                $('#submitbtn').attr("disabled",false);

              } else {

                $('.wrong').addClass('show');
                $('.right').removeClass('show')
              }

            },
             error: function(){
               $('.wrong').addClass('show');
               $('.right').removeClass('show')
            }
          });
        }
      });

      $("#subfrom").submit( function () {
        $('#submitbtn').attr("disabled",true);
      } );
//                  $('#submitbtn').on("click",function(){
//                     $('#submitbtn').attr("disabled",true);
//                    debugger;
//                  })

      </script>
    </body>
  </html>