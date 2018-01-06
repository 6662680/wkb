<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller
{
    public function __construct()
	{
        parent::__construct();
	}

    /**
 * 编辑会员页面
 * @author LiYang
 * @date 2017-9-26
 * @return void
 */
    public function editShow()
    {
        $user_id = decode(base64_decode(I('get.user_id')));

        $user = M('user')->where(['id' => $user_id])->field('mobile, name, head_portrait')->find();

        $this->assign($user);
        $this->display('edit-info');
    }

    /**
     * 系统头像页面
     * @author LiYang
     * @date 2017-9-26
     * @return void
     */
    public function systemHeadPortrait()
    {
        $this->display('select-avatar');
    }

    /**
     * 上传头像截图
     * @author LiYang
     * @date 2017-9-26
     * @return void
     */
    public function uploadHeadPortrait()
    {
        $this->display('cropper');
    }


    /**
     * 编辑会员
     * @author LiYang
     * @date 2017-9-26
     * @return void
     */
    public function edit()
    {
        $data = [];
        $webPost = I('post.');
        $tvPost = getRequest();
        $img = '';

        if ($webPost['user_id']) {
            $post = $webPost;
            $post['user_id'] = decode(base64_decode($webPost['user_id']));

            if (!empty($post['head_portrait'])){

                if ($post['type'] == 2) {
                    $post['head_portrait'] = $post['head_portrait'];
                    $filename = uniqid().".png";
                    $upkey = 'dbspapi/'.date('Ymd').'/';
                    $dir = 'Uploads/'.date('Ymd');
                    $upd_file = $dir.'/'.$filename;
                    if(!is_dir($dir)){
                        mkdir($dir, 0777, true);
                    }
                    $imgdata = explode(',',$post['head_portrait']);

                    file_put_contents($upd_file,base64_decode($imgdata[1]));

                    $upd_keyname = $upkey.$filename;
                    jscloud_upload($upd_file,$upd_keyname);
                    $img = $upd_keyname;
                }

            }
        }
        elseif ($tvPost['user_id']) {
            $post = $tvPost;

        } else {
            returnajax(false, '', '缺少参数');
        }

        if (empty($post['name']) && empty($post['head_portrait'])) {
            returnajax(false, '', '备注名称和头像不可同时为空');
        }


        $user = M('user')->where(['id' => $post['user_id']])->find();

        if (!$user) {
            returnajax(false, '', '该用户不存在');
        }

        $model = M('user');
		$model->id = $user['id'];
        $model->name = $post['name'] ? $post['name'] : $model->name;
        $head_portrait = !empty($post['head_portrait']) ? $post['head_portrait'] : $model->head_portrait;

        if(!empty($_FILES)){
			$img = headimgUpload();
			$model->head_portrait = $img['keyname'];

		}else{
            if($post['type'] == 1){
                $model->head_portrait = $head_portrait;
            }

            if ($post['type'] == 2) {
                $model->head_portrait = $img;
            }

		}
        $img = $model->head_portrait;

        if ($model->save() === false){
            returnajax(false, '', '编辑失败，请稍后再试');
        }

        $data['data']['mobile'] = $post['mobile'];
        $data['data']['name'] = $post['name'];
        $data['data']['head_portrait'] = C('AVATAR_URL'). $img;
        $data['user_id'] = $post['user_id'];
        $data['type'] = 3;// 1添加好友关系, 2编辑好友关系, 3修改自身信息

        $jpush = new \Vendor\Jpush\Jpush();

        if ($user['deviceid_tv']) {
            $jpush->push($user['deviceid_tv'], $data, '只是一个测试');
        }

        if ($user['deviceid_mobile']) {
            $jpush->push($user['deviceid_mobile'], $data, '只是一个测试');
        }

        cache($post['user_id'] . 'AmigosLists', NULL);
        returnajax(true, '', '编辑成功');


    }
}