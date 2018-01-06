<?php
namespace Mobile\Controller;
use Think\Controller;
class BaseController extends Controller
{
    public function __construct()
	{
        parent::__construct();

        $post = getRequest('vaule');
        if (!$post['user_id']) {
            returnajax('false', '', '请登录');
        }

	}

    /**
     * 上传
     *
     * @return void
     */
    public function upload()
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

}