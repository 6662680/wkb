<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class UserController extends Controller
{
    public function __construct()
	{
        parent::__construct();
	}

    /**
     * 注册
     * @author LiYang
     * @param  [mobile]   手机号
     * @param  [password] 密码
     * @date 2018-1-7
     * @return void
     */
    public function register()
    {
        if (!IS_POST) {
            $this->display('register');
			
        } else {
            $data = [];
            $post = I('post.');

            if (S('code'.$post['mobile']) != $post['code']) {
                returnajax(false, '', '验证码不正确');
            }

            if (empty($post['mobile']) && empty($post['password'])) {
                returnajax(false, '', '请完善用户信息');
            }

            if (!regexp('mobile', $post['mobile'])) {
                returnajax(false, '', '手机号格式不正确');
            }

            if (!regexp('password', $post['password'])) {
                returnajax(false, '', '密码格式不正确');
            }

            if (M('user')->where(['mobile' => $post['mobile']])->find() ) {
                returnajax(false, '', '该手机号已被注册');
            }

            $salt = createSalt();
            $password = encryption($post['password'], $salt);
			/*pr(I('post.'));die;*/
			if (I('post.upuser')) {
				$data = [
                'mobile' => $post['mobile'],
                'up_user' => I('post.upuser'),
                'salt' => $salt,
                'password' => $password,
                'create_time' => time(),
                'last_login_time' => time(),
                'last_login_ip' => getIp(),
            	];
			} else {
				$data = [
                'mobile' => $post['mobile'],
                'salt' => $salt,
                'password' => $password,
                'create_time' => time(),
                'last_login_time' => time(),
                'last_login_ip' => getIp(),
            	];
			}
			
            /*$data = [
                'mobile' => $post['mobile'],
                'salt' => $salt,
                'password' => $password,
                'create_time' => time(),
                'last_login_time' => time(),
                'last_login_ip' => getIp(),
            ];*/

            $rst = M('user')->add($data);

            if ($rst) {
            	$this->login();
                D('Log')->addLog('ip' . getIp() . '注册成功!', $rst);
                returnajax(true, '', '注册成功');
            } else {
                returnajax(false, '', '注册失败，请稍候再试');
            }

        }


    }

    /**
     * 登录
     * @author LiYang
     * @param  [mobile]   手机号
     * @param  [password] 密码
     * @date 2018-1-7
     * @return void
     */
    public function login()
    {
        if (!IS_POST) {
            $this->display('login');
        } else {
            $post = I('post.');

            if (!regexp('mobile', $post['mobile'])) {
                returnajax(false, '', '手机号格式不正确');
            }

            if (!regexp('password', $post['password'])) {
                returnajax(false, '', '密码格式不正确');
            }

            $rst = M('user')->where(['mobile' => $post['mobile']])->find();

            if (!$rst) {
                returnajax(false, '', '账号不正确');
            }

            if (encryption($post['password'], $rst['salt']) != $rst['password']) {
                returnajax(false, '', '密码不正确');
            }

            $model = M('user');
            $model->find($rst['id']);
            $model->last_login_time = time();
            $model->last_login_ip = getIp();

            D('log')->addLog('ip' . getIp() . '登陆成功!', $rst['id']);
            session('user_id', $rst['id']);
            session('mobile', $rst['password']);
            returnajax(true, '', '成功');
        }


    }
	/*退出登录*/
	public function logout()
    {
		 session(null);
		 $this->success('退出成功！', U('User/login'));

    }



    public function verificationAddress()
    {
        getwkb('0x19e2fbe87147cb8d7b15b92b0b7e35b906339b6b');
    }


    /**
     * 重置密码
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function reset()
    {
        $mobile = I('post.mobile');

        $rst = M('user')->where(['mobile' => $mobile])->find();

        if (!empty($rst)) {
            returnajax(false, '', '已经注册的手机号');
        }
        $code = generate_code();
        S('code'.$mobile, $code);
    	    // 发送验证码
        Vendor('aliNote.TopSdk','','.php');
        //Vendor('aliNote.Topsdk.php');
        $c = new \TopClient;
        $c ->appkey = 23427369 ;
        $c ->secretKey = 'd0eb6e03b0d3c9dfb369a1659d92698c' ;
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req ->setExtend( "" );
        $req ->setSmsType( "normal" );
        $req ->setSmsFreeSignName( "上饶比特" );
        $req ->setSmsParam( "{name:'$code',time:'2222'}" );
        $req ->setRecNum($mobile);
        $req ->setSmsTemplateCode( "SMS_66675064" );
        $resp = $c ->execute( $req );

        returnajax(true, '', '发送成功');
    }

    
	/**
     * 个人中心地址与提现
     * @author zh
     * @date 2018-1-17
     * @return void
     */
	/*提现申请*/
	public function withdraw() {
        $user_id = session('user_id');
        $user    = M('user')->where(['id' => $user_id])->find();
		/*pr($user);die();*/
        $wpoint  = I('get.wpoint');
		/*pr($wpoint);die();*/
        $data    = [
            'user_id'     => $user_id,
            'wpoint'      => $wpoint,
            'wpoint'      => $wpoint,
            'create_time' => time(),
            'site'        => $user['site'],
        ];
        $list    = M('user_withdraw')->where(['user_id' => $user_id])->select();
		
        if ($list) {
        	foreach ($list as &$value) {
        		/*pr($value['status']);*/
        		if (($value['status'] == 1) || ($value['status'] == 3)) {
        			returnajax(FALSE, '', '已有提现在审核中，不能重复提现!');
        		} else {
        			$thisweek_start=mktime(0,0,0,date('m'),date('d')-date('w'+1),date('Y'));
					$thisweek_end=mktime(23,59,59,date('m'),date('d')-date('w')+7,date('Y'));
					
					if($value['create_time']>=$thisweek_start&&$value['create_time']<$thisweek_end){
						returnajax(FALSE, '', '本周已提现，请下周再来!');
					}
        		}
        	}
        } 
		
		if ((date('w') == 5) || (date('w') == 6)) {
            returnajax(FALSE, '', '周五、周六不允许提现!');
		} 
		if (!$wpoint||!regexp('int',$wpoint)) {
        	returnajax(FALSE, '', '请输入提现数额!');
        } 
		if ($wpoint>$user['point']) {
        	returnajax(FALSE, '', '提现积分大于您的总积分!');
        
        } else {
            M('user_withdraw')->add($data);
            /*添加日志*/
            D('Log')->addLog('会员' . $user_id . '提交提现申请', $user_id);
            returnajax(TRUE, '', '提现申请提交成功!');
        }
    }
	
	/*填写wkc临时地址*/
	public function wSiteTemp() {
        $user_id = session('user_id');
        $user    = M('user')->where(['id' => $user_id])->find();
		/*pr($user);die();*/
        $sitetemp  = I('get.sitetemp');
		/*pr($wpoint);die();*/
        $data    = [
            'sitetemp'     => $sitetemp,
        	];
        
		
        if (!$sitetemp) {
            returnajax(FALSE, '', '请输入您的玩客币地址!');
        } elseif (!regexp('sitetemp',$sitetemp)) {
        	/*pr($data);die;*/
        	returnajax(FALSE, '', '请输入正确格式的地址!');
			
        } else {
            M('user')->where(['id' => $user_id])->save($data);
			
            /*添加日志*/
            D('Log')->addLog('会员' . $user_id . '成功填写临时玩客币地址', $user_id);
			
            returnajax(TRUE, '', '玩客币地址填写成功!');
        }
    }
    /*修改为正式地址*/
    public function site()
    {

    	//访问迅雷接口
    	$user = M('user')->where(['id' => session('user_id')])->find();

        if ($user['site']) {
            returnajax(false, '', '您已经通过验证');
        }

		$userSite = $user['sitetemp'];//getwkb的第一个参数
		$page = 1 ;//先设置为1
		$time = $user['create_time'];

//		$result = getwkb('0xc92fb1c425e40469de1ce4729a32f49949c39b81',C('SITE'),$time, 0, 23);
		$result = getwkb($user['sitetemp'],C('SITE'),$time, 0, 1);

        if (!$result) {
            returnajax(false, '', '没有找到您的打款记录,如果您有疑问，请联系客服');
        }

        $add = M('earnings')->add(['user_id' => session('user_id'), 'price' => $result['price'], 'creation_time' => time(), 'order_id' => $result['order_id']]);

        if (!$add) {
            returnajax(false, '', '打款记录异常,请联系客服');
        }

		/*接口验证成功后执行的代码*/
        $user = M('user')->where(['id' => session('user_id')])->find();
        $rst = M('user')->where(['id' => session('user_id')])->save(['site' => $user['sitetemp']]);

        if ($rst === false) {
            returnajax(false, '', '验证失败，请联系客服');
        } else {
            returnajax(true);
        }
    }



}