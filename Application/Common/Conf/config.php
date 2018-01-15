<?php
return array(
    //'配置项'=>'配置值'
    'DB_TYPE' => 'mysql',     // 数据库类型
    'DB_HOST' => '192.168.2.244', // 服务器地址
    //'DB_HOST' => '127.0.0.1', // 服务器地址
    'DB_NAME' => 'wkb',    // 数据库名
    //'DB_NAME' => 'wankebi',    // 数据库名
    'DB_USER' => 'root',      // 用户名
    'DB_PWD' => 'root',          // 密码
    'DB_PORT' => '3306',        // 端口
    'DB_PREFIX' =>  '',    //前缀
    'URL_MODEL' =>2,

    'URL_CASE_INSENSITIVE' => true,

    'HOST_HOME' => 'xxxx',

	//商品类型
	'COMMODITY_TYPE' => array(
		1 => 'person',
		2 => 'equipment',
		3 => 'mediche',
	),

	'ORDER_TIME' => 600,

	'SERVER_ADDRESS' => 'shouxufeidizhi',
//    'THINK_EMAIL' => array(
//        'SMTP_HOST'   => 'vipmail13.myhostadmin.net', //SMTP服务器
//        'SMTP_PORT'   => '465', //SMTP服务器端口
//        'SMTP_USER'   => 'service@btcoto.com', //SMTP服务器用户名
//        'SMTP_PASS'   => 't6d4c7c4', //SMTP服务器密码
//        'FROM_EMAIL'  => 'service@btcoto.com', //发件人EMAIL
//        'FROM_NAME'   => '李阳', //发件人名称
//        'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
//        'REPLY_NAME'  => '', //回复名称（留空则为发件人名称）
//    ),

    'THINK_EMAIL' => array(
        'SMTP_HOST'   => 'smtp.qq.com', //SMTP服务器
        'SMTP_PORT'   => '465', //SMTP服务器端口
        'SMTP_USER'   => '305665@qq.com', //SMTP服务器用户名
        'SMTP_PASS'   => 'plwmkypuogeacadg', //SMTP服务器密码
        'FROM_EMAIL'  => '305665@qq.com', //发件人EMAIL
        'FROM_NAME'   => '李阳', //发件人名称
        'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
        'REPLY_NAME'  => '', //回复名称（留空则为发件人名称）
    ),

	/*手机推送*/
	'JPUSHMOBILEAPPKEY'=>'b96c0a72a770e2d1b8dc2d43',
	'JPUSHMOBILEMASTERSECRET'=>'7b8f3559cc29179c8b8a6f45',
    'AVATAR_URL'=>'https://img.dangcdn.com/',

//    'DATA_CACHE_PREFIX' => 'Redis_',//缓存前缀
//    'DATA_CACHE_TYPE'=>'Redis',//默认动态缓存为Redis
//    'REDIS_RW_SEPARATE' => true, //Redis读写分离 true 开启
    'MYREDIS_HOST'=>'127.0.0.1', //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
    'MYREDIS_PORT'=>'6379',//端口号
    'MYREDIS_TIMEOUT'=>'300',//超时时间
    'MYREDIS_PERSISTENT'=>false,//是否长连接 false=短连接
    'MYREDIS_AUTH'=>'testtest',//AUTH认证密码

);