<?php

/**
 * 检测输入的验证码是否正确，$code为用户输入的验证码字符串
 * @param  [type]  $code  [description]
 * @param  boolean $reset 是否重置验证码
 * @param  string  $id    [description]
 * @return [type]         [description]
 * @author jlb <[<email address>]>
 */
function check_verify($code, $reset = true, $id = ''){
    $verify = new \Think\Verify(array('reset'=>$reset));
    return $verify->check($code, $id);
}
/**
 * TODO 基础分页的相同代码封装，使前台的代码更少
 * @param $count int 要分页的总记录数
 * @param $pagesize int 每页查询条数
 * @return \Think\Page
 * @author Gison
 * @since  2016年09月02日
 */
function getPage($count, $pagesize = 20, $para = array())
{
    $p = new Think\Page($count, $pagesize, $para);
    $p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
    $p->setConfig('prev', '上一页');
    $p->setConfig('next', '下一页');
    $p->setConfig('last', '末页');
    $p->setConfig('first', '首页');
    $p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
    $p->lastSuffix = false;//最后一页不显示为总页数
    return $p;
}

/**
 * 加密函数
 *
 * @param $str string 要加密的字符串
 * @return string 加密后的字符串
 * @author Gison
 * @since  2016年12月07日
 */
function encrypt($str)
{
    return md5(C('AUTH_CODE') . $str);
}

/**
 * 生成箱子或优惠卷编码
 *
 * @param null
 * @return string 加密后的字符串
 * @author Gison
 * @since  2016年12月07日
 */
function get_encode()
{
    return md5(uniqid());
}

/**
 * 给菜单名生成树状结构
 * @param  [type] $arrTree 菜单数组
 * @param  [type] $step	   层次深度
 * @param  [type] $repeatStr 层次标识字符
 * @return array
 * @author jlb         
 */
function menuArrayTree($arrTree,$step=0,$repeatStr='---- ')
{
	static $trList = array();
	foreach ($arrTree as $v) 
	{
		$v['name'] = str_repeat($repeatStr, $step) . $v['name'];
		$trList[] = $v;
		if ( !empty($v['son']) ) 
		{
			menuArrayTree($v['son'], $step + 1);
		}
	}
	return $trList;
}

/**
 * 打印参数
 * @return void
 */
function pr($content) {
    echo '<pre>';
        print_r($content);
    echo '</pre>';
}

/**
 * 返回ajax
 * @return json
 */
function returnajax($Bool, $data = NULL, $msg = NULL, $code = 0) {
    $json = json_encode(['status' => $Bool, 'msg' => $msg, 'code' => $code, 'data' => $data], true);
    exit($json);
}

//
///**
// * 接值解密,只接key 为value的json数组
// * @return array
// */
//function getRequest($method='post', $debug = APP_DEBUG) {
//
//    if($method == 'get'){
//        $param = I('get.value');
//    }else{
//        $param = I('post.value');
//    }
//
//    $rst =  json_decode(decode($param),true);
//
//    if (is_array($rst)) {
//        session('api', true);
//        return $rst;
//    }
//
//    if ($debug) {
//        session('debug', true);
//        return I('post.');
//    }
//}

/**
 * 创建验证码
 * @param  length
 * @return Boolean
 */
function generate_code($length = 4) {
    $min = pow(10 , ($length - 1));
    $max = pow(10, $length) - 1;
    return rand($min, $max);
}
//
///**
// * 加密
// * @param string $str 要处理的字符串
// * @param string $key 加密Key，为8个字节长度
// * @return string
// */
//function encode($str, $key = 'dbsp2017') {
//    $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
//    $str = pkcs5Pad($str, $size);
//    $aaa = mcrypt_cbc(MCRYPT_DES, $key, $str, MCRYPT_ENCRYPT, $key);
//    $ret = base64_encode($aaa);
//    return $ret;
//}
//
//function pkcs5Pad($text, $blocksize) {
//    $pad = $blocksize - (strlen($text) % $blocksize);
//    return $text . str_repeat(chr($pad), $pad);
//}
//
///**
// * 解密
// * @param string $str 要处理的字符串
// * @param string $key 解密Key，为8个字节长度
// * @return string
// */
//function decode($str, $key = 'dbsp2017') {
//    $strBin = base64_decode($str);
//    $str = mcrypt_cbc(MCRYPT_DES, $key, $strBin, MCRYPT_DECRYPT, $key);
//    $str = pkcs5Unpad($str);
//    return $str;
//}
//
//function pkcs5Unpad($text) {
//    $pad = ord($text {strlen($text) - 1});
//    if ($pad > strlen($text))
//        return false;
//
//    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
//        return false;
//
//    return substr($text, 0, - 1 * $pad);
//}

/*
  * 判断某个数组的某个下标存在且不为false
  * @param $array
  * @param $key
  */
function is_exit($array,$key){
    if(!isset($array[$key])){
        return false;
    }
    return true;
}
    /*
     * 判断某个数组的某个KEY存在且不为false
     * @param $array
     * @param $key
     */
function is_key($array,$keys){
    $lock = true;
    foreach($keys as $key=>$value){
        if(!isset($array[$value])){
            $lock = false;
            return $lock;
        }
    }

    return $lock;
}

/*
     * 各种类型的验证
     * @param check type
     * @param data
     */
function  regexp($rule,$key){
    switch ($rule) {
        case "card" : // 卡号 字母、数字、中文下划线组成 2-36
            return preg_match ( "/^[\x{4e00}-\x{9fa5}A-Za-z0-9]{2,36}$/u", $key);
        case "username" : // 账户名称 字母、数字、中文下划线组成 2-6
            return preg_match ( "/^[\x{4e00}-\x{9fa5}A-Za-z0-9]{2,6}$/u", $key);
        case "password" : // 密码
            return preg_match ( "/^(\S){6,20}$/", $key );
        case "int" : // 数字
            return preg_match ( "/^\d{1,11}$/", $key );
        case "tradepassword" : // 交易密码
            return preg_match ( "/^(\S){6}$/", $key );
        case "md5password" : // 密码
            return preg_match ( "/^(\S){32}$/", $key );
        case "zhcn" : // 中文
            return preg_match ( "/[\u4e00-\u9fa5]/", $key );
        case "tel" : // 国内座机电话号
            return preg_match ( "/\d{3}-\d{8}|\d{4}-\d{7,8}/", $key );
        case "mobile"://手机号
            return preg_match('/^[1][3578][0-9]{9}$/',$key);
        case "qq" : // QQ号
            return preg_match ( "/^[1-9][0-9]{4,}$/", $key );
        case "numberInteger" : // 整形数字
            return preg_match ( "/^[-+]?[1-9]\d*\.?[0]*$/", $key );
        case "numberFloat" : // 浮点型数字
            return preg_match ( "/^[+-]?\d+(\.\d+)?$|^$|^(\d+|\-){7,}$/", $key );
        case "email" : // email
            return preg_match ( "/^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$/", $key );
        case "cid" : // 18位身份证号
            return preg_match ( "/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X|x)$/", $key );
        case "zipcode" : // 国内邮编
            return preg_match ( "/^[1-9]\d{5}(?!\d)$/", $key );
        case "url" : // 网址
            return preg_match ( "/^(ht|f)tp(s?)\:\/\/[0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*(:(0-9)*)*(\/?)([a-zA-Z0-9\-\.\?\,\'\/\\\+&amp;%\$#_]*)?$/", $key );
        case "htmlHexCode" : // html颜色代码，如：#fff0
            return preg_match ( "//^#([a-fA-F0-9]){3}(([a-fA-F0-9]){3})?$/", $key );
        case "IP" : // ip地址
            return preg_match ( "/^(\d|[01]?\d\d|2[0-4]\d|25[0-5])\.(\d|[01]?\d\d|2[0-4] \d|25[0-5])\.(\d|[01]?\d\d|2[0-4]\d|25[0-5])\.(\d|[01]?\d\d|2[0-4] \d|25[0-5])$/", $key );
        case "macAddress" : // 主机mac地址
            return preg_match ( "/^([0-9a-fA-F]{2}:){5}[0-9a-fA-F]{2}$/", $key );
        case "name" : // 姓名验证
            return preg_match ( "/^[\x{4e00}-\x{9fa5}A-Za-z0-9]{2,8}$/u", $key);
        case "address" : // 用户居住地址
            return preg_match ( "/^[\x{4e00}-\x{9fa5}A-Za-z0-9]{10,50}$/u", $key);
    }
    return false;
}

function validation($data, $post) {
    if (!$data || !$post) {
        return FALSE;
    }
    $verModel = new \Hv1\Model\ValidationModel;
    return $verModel->verification($data, $post);
}
  
function upload()
{
	$upload = new \Think\Upload();
	$upload->maxSize = 10485760;
	$upload->exts = explode(',', 'jpg,gif,png,jpeg');
	$upload->rootPath = './Uploads/';
	$upload->saveName = array('uniqid','');
	$upload->autoSub = true;
	$upload->subName = array('date','Ymd');
	$info = $upload->upload();

	$rst = array();

	if (!$info) {
		$rst['success'] = false;
		$rst['errorMsg'] = $upload->getError();
	} else {
		$rst['success'] = true;
		$rst['info'] = $info;
	}

	return $rst;
}
	
//用户头像上传
function headimgUpload(){
	$info =  upload();
	$upkey = 'dbspapi/'.date('Ymd').'/';
	$upd_file = 'Uploads/'.$info['info']['head_portrait']['savepath'].$info['info']['head_portrait']['savename'];
	$upd_keyname = $upkey.$info['info']['head_portrait']['savename'];
	jscloud_upload($upd_file,$upd_keyname);
	$data['keyname'] = $upd_keyname;
	return $data;
}

//上传至金山云
function jscloud_upload($file,$key){
	
	include_once ("Plugin/ks3-php-sdk-master/Ks3Client.class.php");
	$ak = 'rjiWUdEb0ZIZgfyrMdI0';
	$sk = 'eoTExoEpFRgAuViVHpH+PXfzF/xlW+ApAsRTytku';
	$endpoint = 'ks3-cn-beijing.ksyun.com';
	$client = new \Ks3Client($ak,$sk,$endpoint);
	$bucket_name = "dangbeiimgv";
	
	// $content = fopen($file,"r");
	// $filesize = filesize($file);
	$args = array(
		"Bucket" => $bucket_name,
		"Key" => $key,
		"Content" => array(
			"content" => $file,
			"seek_position" => 0
		),
		"ACL" => "public-read",
		"ObjectMeta" => array(
			"Content-Type" => "image/jpg",
			// "Content-Length" => $filesize
		)
	);
	$upRst = $client->putObjectByFile($args);
}

function cache($key,  $value = '') {

    if (!APP_DEBUG) {
        return false;
    }
    $redis = new \Redis();
    $redis->connect(C('MYREDIS_HOST'),C('MYREDIS_PORT'));

    if (C('MYREDIS_AUTH')) {
        $auth = $redis->auth(C('MYREDIS_AUTH')); //设置密码
    }

    if (is_null($value)) {
        $redis->delete($key);
        return ;
    }

    if ($value) {
        $redis->set($key, json_encode($value));
    } else {
        $rst = $redis->get($key);
        return  json_decode($rst, true);
    }
}
/*极光推送*/
function tuisong($deviceid, $data, $key = NULL, $stersecret = NULL) {

    if ($deviceid || is_array($data)) {
        return false;
    }

    $jpush = new \Vendor\Jpush\Jpush($key, $stersecret);
    $rst = $jpush->push($deviceid,$data, '只是一个测试');
    return $rst;
}

/*网易云信*/
function yunxin() {
    Vendor('wangyiyun.ServerAPI');
    $serverAPI = new \ServerAPI(C(YUNXIN_APPKey),C(YUNXIN_AppSecret),'curl');
    return $serverAPI;
}

/*发送短信*/
function sms($mobile, $nubmer, $data) {
    Vendor('AliyunMns.mns');
    $result = run($mobile, $nubmer, $data);
    return $result;
}

///*删除关系缓存*/
//function delrelcache($map) {
//    $dbnum = USERINFO_DBNUM;
//    $redisOpt = new \Common\Controller\RedisoptController('server1',$dbnum);
//    $redisOpt->delKeyCache(USERREL_KEY.md5($map));
//}
//
///*删除列表缓存*/
//function dellistcache($id) {
//    $dbnum = USERINFO_DBNUM;
//    $redisOpt = new \Common\Controller\RedisoptController('server1',$dbnum);
//    $redisOpt->delKeyCache(USERREL_list_M_KEY.$id);
//    $redisOpt->delKeyCache(USERREL_list_T_KEY.$id);
//}
//
///*删除用户缓存*/
//function delinfocache($map) {
//    $dbnum = USERINFO_DBNUM;
//    $redisOpt = new \Common\Controller\RedisoptController('server1',$dbnum);
//    $redisOpt->delKeyCache(USERINFO_KEY.md5($map));
//}
//
//function dellogcache($id) {
//    $dbnum = USERLOG_DBNUM;
//    $userlog_key = USERREL_LOG_KEY.md5($id);
//    $redisOpt = new \Common\Controller\RedisoptController('server1',$dbnum);
//    $redisOpt->delKeyCache($userlog_key);
//}
//
//function delloglistcache($id) {
//    $dbnum = USERLOG_LIST_DBNUM;
//    $userlog_key = USERREL_LOG_LIST_KEY.md5($id);
//    $redisOpt = new \Common\Controller\RedisoptController('server1',$dbnum);
//    $redisOpt->delKeyCache($userlog_key);
//}


function createSalt() {
    $chars = [
        "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K",
        "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V",
        "W", "X", "Y", "Z", "*", "%", "#", "@", "1", "2", "3",
        "4", "5", "6", "7", "8", "9", "?", "a", "b", "c", "d",
        "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o",
        "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"
    ];
    shuffle($chars);
    $salt = "";
    for ($i = 0; $i < 25; $i++) {
        $salt .= $chars[$i];
    }
    return $salt;
}

function encryption($pwd, $salt) {
    $options = [
        'cost' => 12,
        'salt' => $salt
    ];
    $password_hash = password_hash($pwd, PASSWORD_BCRYPT, $options);
    $md5 = '';
    $salt_arr = str_split($salt);
    for ($i = 1; $i < 25; $i++) {
        if ($i % 5 == 0) {
            $md5.=$salt_arr[$i];
        }
    }
    return md5($password_hash . $md5);
}
function forgetPassword($email,$salt){

    return md5($email.$salt);
}

/**
 * 系统邮件发送函数
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
 * @return boolean
 */
//function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null){
//    $config = C('THINK_EMAIL');
//    vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
//    $mail             = new PHPMailer(); //PHPMailer对象
//    $mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
//    $mail->IsSMTP();  // 设定使用SMTP服务
//    $mail->SMTPDebug  = 0;                     // 关闭SMTP调试功能
//    // 1 = errors and messages
//    // 2 = messages only
//    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
//    $mail->SMTPSecure = 'ssl';                 // 使用安全协议
//    $mail->Host       = $config['SMTP_HOST'];  // SMTP 服务器
//    $mail->Port       = $config['SMTP_PORT'];  // SMTP服务器的端口号
//    $mail->Username   = $config['SMTP_USER'];  // SMTP服务器用户名
//    $mail->Password   = $config['SMTP_PASS'];  // SMTP服务器密码
//    $mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
//    $replyEmail       = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
//    $replyName        = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];
//    $mail->AddReplyTo($replyEmail, $replyName);
//    $mail->Subject    = $subject;
//    $mail->MsgHTML($body);
//    $mail->AddAddress($to, $name);
//    if(is_array($attachment)){ // 添加附件
//        foreach ($attachment as $file){
//            is_file($file) && $mail->AddAttachment($file);
//        }
//    }
//    return $mail->Send() ? true : $mail->ErrorInfo;
//}

function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null){

    $config = C('THINK_EMAIL');

    vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
    vendor('SMTP');
    $mail = new PHPMailer(); //PHPMailer对象

    $mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码

    $mail->IsSMTP(); // 设定使用SMTP服务

    $mail->SMTPDebug = 0; // 关闭SMTP调试功能

// 1 = errors and messages

// 2 = messages only

    $mail->SMTPAuth = true; // 启用 SMTP 验证功能

    $mail->SMTPSecure = 'ssl'; // 使用安全协议

    $mail->Host = $config['SMTP_HOST']; // SMTP 服务器

    $mail->Port = $config['SMTP_PORT']; // SMTP服务器的端口号

    $mail->Username = $config['SMTP_USER']; // SMTP服务器用户名

    $mail->Password = $config['SMTP_PASS']; // SMTP服务器密码

    $mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);

    $replyEmail = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];

    $replyName = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];

    $mail->AddReplyTo($replyEmail, $replyName);

    $mail->Subject = $subject;

    $mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";

    $mail->MsgHTML($body);

    $mail->AddAddress($to, $name);

    if(is_array($attachment)){ // 添加附件

        foreach ($attachment as $file){

            is_file($file) && $mail->AddAttachment($file);

        }

    }

    return $mail->Send() ? true : $mail->ErrorInfo;

}
//生成32位订单号
function orderNubmer($id) {
    return md5(uniqid().substr(ip2long($id), -6).rand(1000,9999));
}

function vcode() {
    $Verify = new \Think\Verify();
    $Verify->fontSize = 30;
    $Verify->length   = 4;
    $Verify->expire = 1800;
    $Verify->useNoise = false;
    return $Verify->entry();
}

function getIp() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else
        if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else
            if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
                $ip = getenv("REMOTE_ADDR");
            else
                if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
                    $ip = $_SERVER['REMOTE_ADDR'];
                else
                    $ip = "unknown";
    return ($ip);
}

function getwkb($site, $page) {
    $url = "https://walletapi.onethingpcs.com/getTransactionRecords";
    $post_data =  '["'.$site.'","0","0","'.$page.'","10"]';
    echo $post_data;
    $ch = curl_init();
    $this_header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");//访问链接时要发送的头信息
    $this_header[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:52.0) Gecko/20100101 Firefox/52.0';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    // post数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // post的变量
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    $output = curl_exec($ch);
    curl_close($ch);

    //打印获得的数据
    print_r($output);
}

