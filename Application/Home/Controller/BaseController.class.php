<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller
{
    public function __construct()
	{
        parent::__construct();
        $this->check_login();
	}


    public function check_login()
    {
        if (empty(session('user_id'))) {
           redirect(U('home/user/login'));
        }
    }

    public function error()
    {
        $this->display('base/404');
        die();
    }

    public function ban()
    {
//        if (date('G') >= 23 ||  date('G') < 1) {
//            returnajax(false, '' , '抱歉，为了方便结算,每天23-凌晨1点期间不允许有任何操作');
//        }
    }

}