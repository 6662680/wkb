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


}