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
          <div class="col-sm-10 col-sm-offset-1"  style="text-align: center">
            <button>点此发送订单号到邮箱</button>
          </div>
          <?php if($type == '卖出' ): ?><div class="col-sm-10 col-sm-offset-1 mb60">
              <p>请在<span class="redline">15分钟</span>内将比特币汇至</p>
              <div class="alert alert-warning brline" >
                <?php echo ($btc_address); ?>
              </div>
              <p>若超过15分钟,请联系客服给予确认,客服联系方式:
                  <span>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=470044735&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:470044735:51" alt="点击这里给我发消息" title="点击这里给我发消息"/></a>
                  </span>
              </p>
              <!-- 请替换qq号 -->
              <p>我们将在确认后给您打款.</P>
            </div>
            <div class="col-sm-10 col-sm-offset-1">
              <h3 class="mb20 text-center">请核对你的收款方式</h3>
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
          <?php else: ?>
            <div class="col-sm-10 col-sm-offset-1"  style="text-align: center">
              <h3 class="mb20 text-center">请将款项 <?php echo ($total); ?> 汇至如下<?php echo ($amount); ?> 并附上留言:订单号<?php echo ($order_number); ?></h3>
              <p class="text-center f30">
                <p>收款人姓名：<?php echo ($sell_name); ?></p>
                <p>收款人<?php echo ($amount); ?>号：<?php echo ($sell_card); ?></p>
                <?php if($sell_bank): ?><p>银行开户行：<?php echo ($sell_bank); ?></p><?php endif; ?>
                <p class="input-hint alerta">*若信息有误,请返回首页重新下单.</p>
              </p>
              <hr class="hrpa">
            </div>
            <div class="col-sm-10 col-sm-offset-1">
              <h3 class="mb20 text-center">请核对你的比特币钱包地址</h3>
              <p class="text-center f30">
                <span class="sellnum"><?php echo ($receive_address); ?></span>
              </p>
              <hr class="hrpa">
            </div>
            <div class="col-sm-10 col-sm-offset-1">
              <h3 class="mb20 text-center">请核对你的付款方式</h3>
              <p class="text-center f30">
                <?php echo ($amount); ?>
              </p>
              <hr class="hrpa">
            </div>
            <div class="col-sm-10 col-sm-offset-1" style="text-align: center">
              <h3 class="mb20 text-center">请核对你的付款信息</h3>
              <p class="text-center f30">
                <p>您的姓名:<?php echo ($name); ?></p>
                </br>
                <p>您的<?php echo ($amount); ?>号:<?php echo ($card); ?></p>
              </p>
              <hr class="hrpa">
            </div>
            <div class="col-sm-10 col-sm-offset-1">
              <h3 class="mb20 text-center">请核对你的购买数量</h3>
              <p class="text-center f30">
                <span class="sellnum"><?php echo ($num); ?></span>BTC
              </p>
              <hr class="hrpa">
            </div><?php endif; ?>


          <div class="col-sm-10 col-sm-offset-1">
              <h3 class="mb20 text-center">请核对你的交易金额</h3>
              <p class="text-center f30">
                人民币<span class="sellnum"><?php echo ($total); ?></span>元
              </p>
              <hr class="hrpa">
          </div>
            
            <!-- <div class="col-sm-10 col-sm-offset-1">
              <h3 class="mb20 text-center">请核对你的收币地址</h3>
              <p>btc钱包地址</>
                <div class="alert alert-warning brline" >
                  pasdasdasdhjgasdhkajsdb,basdashjkdhasd,mn,sdnasdkhsdnasdkhsdnasdkhsdnasdkhsdnasdkhsdnasdkhsdnasdkhsdnasdkhsdnasdkhsdnasdkh
                </div>
                <p class="input-hint alerta">若信息有误，请勿付款</p>
                <hr class="hrpa">
              </div> -->


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
      <script>
        $('button').click(function(){
          $.ajax({
            type: "post",
            url: "<?php echo U('Index/sendOrder');?>",//这里填写url
            data: {
              code: 1,
            },
            dataType:'json',
            success: function(data) {
              console.log(data);
              if (data.code == 1) {
                  var time = 60 - data.time;
                $('button').html(time);
              }

              if (data.code == 2) {
                  alert('页面等待超时,请返回首页重新下单');
                  window.location.href= C('HOST_HOME');
              }

              if (data.code == 0) {
                $('button').html(60);
                $('button').attr('disabled',true);
              }

              if (data.code == 3) {
                alert('发送失败，请等待5秒后再次重新点击');
                $('button').html(5);
                $('button').attr('disabled',true);
              }
            },
          });
        });

        setInterval(function (){
          var num = $('button').html();

          if(num==0){
            //clearInterval(interval);
          }
          num = num--;
          $('button').html(num);

        },1000);

      </script>
    </body>
  </html>