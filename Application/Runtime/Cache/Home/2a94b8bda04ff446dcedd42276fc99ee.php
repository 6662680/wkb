<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title></title>
    <!-- head 中 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.2/style/weui.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.0/css/jquery-weui.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../../Public/css/main.css">
</head>

<body>
    <div class="page home ">
        <div class="page__hd">
            <h1 class="page__title">
                背包
            </h1>
            <div class="navbar sd">
            <div class="navbar__item weui_bar__item_on">
                <a href="index.html">人物</a>
            </div>
            <div class="navbar__item ">
                <a href="../wanke/item.html">装备</a>
            </div>
            <div class="navbar__item ">
                <a href="../wanke/food.html">食物</a>
            </div>
                <div class="navbar__item pull-right">
                    <a href="">
                        <i class="fa fa-filter mr5" aria-hidden="true"></i>等级</a>
                </div>
            </div>
        </div>
        <div class="page__bd page__bd_spacing ">
        <ul class="sd">
            <a href="../wanke/main.html">
                <li>
                    <div class="mainflex js_category bgw">

                        <div class="mainphoto c1">
                            <!-- 头像 -->
                            <img src="http://placehold.it/300x300" alt="">
                        </div>
                        <div class="maintext">
                            <ul>
                                <li>#12121
                                </li>
                                <li class="dark">等级:
                                    <span>lv10</span>
                                </li>
                                <li class="dark">挖矿能力:
                                    <span>999</span>
                                </li>
                                <li class="dark">昨日产量:
                                    <span>1200</span>
                                </li>
                            </ul>
                        </div>

                        <div class="cleanfix"></div>
                    </div>
                </li>
            </a>

        </ul>
            <ul class="sd">
                <a href="../wanke/main.html">
                    <li>
                        <div class="mainflex js_category bgw">

                            <div class="mainphoto c1">
                                <!-- 头像 -->
                                <img src="http://placehold.it/300x300" alt="">
                            </div>
                            <div class="maintext">
                                <ul>
                                    <li>#12121
                                    </li>
                                    <li class="dark">等级:
                                        <span>lv10</span>
                                    </li>
                                    <li class="dark">挖矿能力:
                                        <span>999</span>
                                    </li>
                                    <li class="dark">昨日产量:
                                        <span>1200</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="cleanfix"></div>
                        </div>
                    </li>
                </a>

            </ul>
    </div>
        <div class="weui-tabbar">
            <a href="index.html" class="weui-tabbar__item weui-bar__item--on">
                <div class="weui-tabbar__icon">
                    <i class="fa fa-gamepad"></i>
                </div>
                <p class="weui-tabbar__label">背包</p>
            </a>
            <a href="../wanke/sc.html" class="weui-tabbar__item">
                <div class="weui-tabbar__icon">
                    <i class="fa fa-shopping-bag"></i>
                </div>
                <p class="weui-tabbar__label">商城</p>
            </a>
            <a href="../wanke/want.html" class="weui-tabbar__item">
                <div class="weui-tabbar__icon">
                    <i class="fa fa-gavel"></i>
                </div>
                <p class="weui-tabbar__label">交易</p>
            </a>
            <a href="../wanke/me.html" class="weui-tabbar__item ">
                <div class="weui-tabbar__icon">
                    <i class="fa fa-user"></i>
                </div>
                <p class="weui-tabbar__label">我的</p>
            </a>
        </div>
    </div>
    <!-- body 最后 -->
    <script src="https://cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery-weui/1.2.0/js/jquery-weui.min.js"></script>
    <!-- 如果使用了某些拓展插件还需要额外的JS -->
    <script src="https://cdn.bootcss.com/jquery-weui/1.2.0/js/swiper.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery-weui/1.2.0/js/city-picker.min.js"></script>
</body>

</html>