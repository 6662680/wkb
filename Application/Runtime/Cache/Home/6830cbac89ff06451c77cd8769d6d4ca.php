<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>添加通讯录</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        html {
            width: 100%;
            height: 100%;
            max-width: 980px;
            margin: 0 auto;
        }
        body {
            width: 100%;
            height: 100%;
            max-width: 980px;
            background-color: #fff;
            font-family: "微软雅黑";
        }
        .peo {
            margin: 0 auto;
            width: 22%;
            height: 24%;
            padding-top: 9%;
        }
        form {
            width: 100%;
            height: 100%;
            min-height: 590px;
        }
        .peo-pic {
            width:100%;
            height: 66%;
            position: relative;
            margin-bottom: 20%;
        }
        .peo-pic input {
            position: absolute;
            display: inline-block;
            opacity: 0;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
        }
        .peo-pic img {
            width: 100%;
            height: 100%;
            border: none;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 3;
            border-radius: 50%;
        }
        .peo .sub {
            display: inline-block;
            width: 100%;
            height: 20%;
            border: 1px solid #8054EC;
            border-radius: 50px;
            background-color: #ffffff;
            color: #8054EC;
            outline: none;
        }
        .cont {
            width: 82%;
            height: 30%;
            margin: 0 auto;
            margin-top: 10%;
        }
        .cont-txt {
            width: 100%;
            height: 60%;
            margin-bottom: 13%;
        }
        .cont-txt input{
            width: 100%;
            height: 34%;
            border: none;
            background: #EEEEEE;
            border-radius: 50px;
            margin-bottom: 3%;
            padding-left: 5%;
            outline: none;
            font-size: 14px;
            color: #999999;
            letter-spacing: 0.53px;
        }
        .cont .sub {
            display: inline-block;
            width: 100%;
            height: 20%;
            border: none;
            background-image: linear-gradient(90deg, #8753EC 0%, #5D5BED 100%);
            border-radius: 50px;
            background-color: #8753EC;
            font-size: 14px;
            color: #FFFFFF;
            letter-spacing: 0.53px;
        }
        .bac {
            position: fixed;
            display: none;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 4;
            background-color: rgba(0,0,0,.5);
        }
        .bac-cont {
            width: 92%;
            height: 36%;
            background-color: #fff;
            border-radius: 6px;
            position: absolute;
            top: 50%;
            left: 50%;
            padding: 5% 6%;
            text-align: center;
            -webkit-transform: translate(-50%,-50%);
            -moz-transform: translate(-50%,-50%);
            -ms-transform: translate(-50%,-50%);
            -o-transform: translate(-50%,-50%);
            transform: translate(-50%,-50%);
        }
        .bac-cont h3 {
            font-size: 14px;
            color: #666666;
            letter-spacing: 0.53px;
            width: 100%;
        }
        .bac-cont h3 span {
            display: inline-block;
            width: 5px;
            height: 3px;
            vertical-align: middle;
            background-image: linear-gradient(90deg, #8753EC 0%, #5D5BED 100%);
            border-radius: 100px;
        }
        .bac-cont ul {
            list-style: none;
            width: 100%;
            margin-top: 7%;
        }
        .bac-cont ul li {
            width: 16%;
            float: left;
            margin-right: 5%;
            margin-bottom: 4%;
            position: relative;
        }
        .bac-cont ul li.active span {
            position: absolute;
            top: 0px;
            right: 0px;
            display: inline-block;
            width: 16px;
            height: 16px;
            background: url("/Public/static/headportrait/icon-select.png") no-repeat center / 100% 100%;
        }
        .bac-cont ul li img {
            width: 100%;
            height: 100%;
        }
        .bac-cont button {
            display: inline-block;
            width: 40%;
            height: 17%;
            background-image: linear-gradient(90deg, #8753EC 0%, #5D5BED 100%);
            border-radius: 100px;
            border: none;
            outline: none;
            margin: 0 auto;
            font-size: 16px;
            color: #FFFFFF;
        }
        .again .a-cont {
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%,-50%);
            -moz-transform: translate(-50%,-50%);
            -ms-transform: translate(-50%,-50%);
            -o-transform: translate(-50%,-50%);
            transform: translate(-50%,-50%);
            width: 92%;
            height: 110px;
            background-color: #fff;
            border-radius: 6px;
            padding: 10px 0;
        }
        .again .a-cont h3 {
            text-align: center;
            font-size: 16px;
            color: #333;
            line-height: 45px;
            border-bottom: 1px solid #ccc;
        }
        .again .a-cont p {
            line-height: 45px;
            font-size: 14px;
            color: #333;
        }
        .again .a-cont p span {
            width: 50%;
            display: inline-block;
            text-align: center;
        }
    </style>
</head>
<body>
    <form action="" id="form">
        <div class="peo">
                <div class="peo-pic">
                    <input type="file" class="filepath" id="file" name="file" data-file="" value="">
                    <img src="<?php echo $head_portrait?>" class="img">
                </div>
                <input type="button" id="subBtn" class="sub" value="点击修改">
        </div>
        <div class="cont">
                <div class="cont-txt">
                    <input type="tel" id="mobile" value="<?php echo $mobile?>" placeholder="请输入手机号码">
                    <input type="text" id="name" value="<?php echo $name?>" placeholder="请输入昵称">
                </div>
                <button  id="queding" class="sub" >确定</button>
        </div>
    </form>

    <div class="bac">
        <div class="bac-cont">
            <h3><span style="margin-right: 7px;"></span>请选择您喜欢的头像<span style="margin-left: 7px;"></span></h3>
            <ul>
                <li class=""><img src="/Public/static/headportrait/Faces_grandpa.png" alt=""><span></span></li>
                <li><img src="/Public/static/headportrait/Faces_grandma.png" alt=""><span></span></li>
                <li><img src="/Public/static/headportrait/Faces_dad.png" alt=""><span></span></li>
                <li><img src="/Public/static/headportrait/Faces_mom.png" alt=""><span></span></li>
                <li style="margin: 0;"><img src="/Public/static/headportrait/Faces_uncle.png" alt=""><span></span></li>
                <li><img src="/Public/static/headportrait/Faces_aunt.png" alt=""><span></span></li>
                <li><img src="/Public/static/headportrait/Faces_belderbrother.png" alt=""><span></span></li>
                <li><img src="/Public/static/headportrait/Faces_beldersister.png" alt=""><span></span></li>
                <li><img src="/Public/static/headportrait/Faces_youngerbrother.png" alt=""><span></span></li>
                <li style="margin: 0;"><img src="/Public/static/headportrait/Faces_youngersister.png" alt=""><span></span></li>
                <div style="clear: both"></div>
            </ul>
            <button id="sureBtn">确定</button>
        </div>
    </div>
    <!--重新选择-->
    <div class="again" style="display: none">
        <div class="a-cont">
            <h3>提交成功！</h3>
            <p><span style="border-right: 1px solid #ccc" id="againBtn">继续编辑</span><span id="outBtn">退出</span></p>
        </div>
    </div>
</body>
</html>
<script src="/Public/js/jquery-3.1.0.min.js"></script>
<script>

    $(function () {

        $("#againBtn").on("click",function () {
            $(".again").hide();

        });
        $("#outBtn").on("click",function () {
            $(".again").hide();
            window.opener=null;
            window.open('','_self');
            window.close();
        });

        function GetQueryString(name)
        {
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if(r!=null)return  unescape(r[2]); return null;
        }

        //照片正反面
        var imgSrc;

        $("#subBtn,.img").on("click",function () {
           imgSrc = "";
           $(".bac").show();
        });
//        选择头像
        $(".bac-cont li").on("click",function () {
           $(this).addClass("active").siblings().removeClass("active");
           imgSrc = $(this).find("img").attr("src");
        });
        $("#sureBtn").on("click",function () {
            $(".peo-pic img").attr("src",imgSrc);
            $(".filepath").attr("data-file",imgSrc);
            $(".bac").hide();
        })

        $('#queding').on("click",function () {
            $('#queding').html('提交中...')
            $('#queding').attr("disabled",true);
            $.post("edit",
                {
                    mobile:$('#mobile').val(),
                    file:$('#file').data('file'),
                    name:$('#name').val(),
                    user_id:GetQueryString("user_id"),
                },
                function(data,status){
                    data = $.parseJSON(data);
                    $('#queding').css('disabled', 'false')

                    if (data.status) {
                        $('.again').css('display', 'block');
                    } else {
                        alert(data.msg)
                    }

                    $('#queding').html('确定')
                    $('#queding').attr("disabled", false);
                });
            return false;
        });
    });


</script>