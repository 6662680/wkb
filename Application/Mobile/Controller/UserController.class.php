<?php
namespace Mobile\Controller;
use Think\Controller;
class UserController extends Controller
{
    public function __construct()
	{
        parent::__construct();
	}
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

    /**
     * 编辑会员
     * @author LiYang
     * @date 2017-9-26
     * @return void
     */
    public function editShow()
    {
        $user_id = decode(I('get.user_id'));

        $user = M('user')->where(['id' => $user_id])->field('mobile, name, head_portrait')->find();

        $this->assign($user);
        $this->display('index');
    }

    public function edit()
    {
        $data = [];
       
		$post = getRequest();
        if (empty($post['user_id'])) {
            returnajax(FALSE,'' , '缺少参数');
        }

        if (empty($post['name']) && empty($post['mobile'])) {
            returnajax(FALSE,'' , '备注名称和手机号不可同时为空');
        }

        $user = M('user')->where(['id' => $post['user_id']])->find();

        if (!$user) {
            returnajax(false, '', '该用户不存在');
        }

        $model = M('user');
		$model->id = $user['id'];
        $model->name = $post['name'] ? $post['name'] : $model->name;
        $model->mobile = $post['mobile'] ? $post['mobile'] : $model->mobile;


        if(!empty($_FILES)){
            $img = headimgUpload();
            $model->head_portrait = $img['keyname'];
        }
        $img = $model->head_portrait;

        if ($model->save() === false){
            returnajax(false, '', '编辑失败，请稍后再试');
        }

        if ($user['deviceid_tv']) {
            $data['data']['mobile'] = $post['mobile'];
            $data['data']['name'] = $post['name'];
            $data['data']['head_portrait'] = C('AVATAR_URL').  $img;
            $data['user_id'] = $post['user_id'];
            $data['type'] = 3;// 1添加好友关系, 2编辑好友关系, 3修改自身信息
            // $jpush = new \Vendor\Jpush\Jpushmobile();
            // $rst = $jpush->push($user['deviceid_mobile'], $data, '只是一个测试');
            $jpush = new \Vendor\Jpush\Jpush();
            $jpush->push($user['deviceid_tv'], $data, '只是一个测试');
        }

        cache($post['user_id'] . 'AmigosLists', NULL);
        returnajax(true, array('head_portrait'=>$data['data']['head_portrait']), '编辑成功');


    }
	
	//用户头像上传
    public function headimgUpload(){
		$info =  $this->upload();
		$upkey = 'dbspapi/'.date('Ymd').'/';
		$upd_file = 'Uploads/'.$info['info']['head_portrait']['savepath'].$info['info']['head_portrait']['savename'];
        $upd_keyname = $upkey.$info['info']['head_portrait']['savename'];
		$this->jscloud_upload($upd_file,$upd_keyname);
        $data['keyname'] = $upd_keyname;
        return $data;
	}

    //上传至金山云
    public function jscloud_upload($file,$key){
        
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
}