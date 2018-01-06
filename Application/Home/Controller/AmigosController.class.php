<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class AmigosController extends Controller

{
    public function __construct()
	{
        parent::__construct();

	}

    /**
     * 显示编辑和添加联系人H5
     * @author LiYang
     * @param  [user_id]  自身ID
     * @param  [mobile]   手机号
     * @param  [name]     姓名
     * @date 2017-9-26
     * @return void
     */
    public function show()
    {
        $relation_id = decode(base64_decode(I('get.relation_id')));

        $relation = M('relation as r')
            ->join('left join `dbsp_user` on dbsp_user.mobile = r.mobile')
            ->where(['r.id' => $relation_id])
            ->field('r.mobile, r.name, dbsp_user.head_portrait')
            ->find();

        if ($relation_id) {
            $relation['title'] = '编辑联系人';
        } else {
            $relation['title'] = '添加联系人';
        }

        $this->assign($relation);
        $this->display('index');
    }

    /**
     * 添加关系
     * @author LiYang
     * @param  [user_id]  自身ID
     * @param  [mobile]   手机号
     * @param  [name]     姓名
     * @date 2017-9-26
     * @return void
     */
    public function add()
    {
        $data = [];
        $post = I('post.');

        $post['user_id'] = decode(base64_decode($post['user_id']));

        if (empty($post['user_id'])) {
            returnajax(FALSE, '', '缺少参数');
        }

        if (empty($post['mobile'])) {
            returnajax(FALSE, '', '手机号不能为空');
        }

        if (empty($post['name'])) {
            returnajax(FALSE, '', '备注名称不能为空');
        }

        $user = M('user')->where(['id' => $post['user_id']])->find();
        $friend = M('user')->where(['mobile' => $post['mobile']])->find();
        $head_portrait = $friend['head_portrait'];

        if (!$friend) {
            returnajax(FALSE, '', '该用户没有注册');
        }

        if ($friend['id'] == $post['user_id']) {
            returnajax(FALSE, '', '不可添加自己');
        }

        $myrst = M('relation')->where(['user_id' => $post['user_id'], 'mobile' => $post['mobile']])->find();

        if ($myrst && $myrst['display'] == 1) {
            returnajax(FALSE, '', '已存在的好友');
        }

        if ($myrst && $myrst['display'] == 2) {
            $save['display'] = 1;
            $save['name'] = $post['name'];

            $rst = M('relation')->where(['user_id' => $post['user_id'], 'mobile' => $post['mobile']])->save(['display' => 1, 'name' => $post['name']]);

            if ($rst !== false) {
                $result = true;
            } else {
                $result = false;
            }

            $myrst = $myrst['id'];

        } else {
            /* 判断自己是否存在对方好友中，若不在则添加*/
            if (!M('relation')->where(['user_id' => $friend['id'], 'mobile' => $user['mobile']])->find()) {
                $model = M('relation');
                $model->user_id = $friend['id'];
                $model->mobile = $user['mobile'];
                $model->display = 2;
                $model->name = '';
                $model->add();
            }

            $model = M('relation');
            $model->user_id = $post['user_id'];
            $model->mobile = $post['mobile'];
            $model->name = $post['name'];;
            $model->display = 1;
            $myrst = $model->add();

            if ($myrst) {
                $result = true;
            } else {
                $result = false;
            }
        }

        if (!$result) {
            returnajax(FALSE, '', '添加失败，请稍后再试');
        } else {
            $user = M('user')->where(['id' => $post['user_id']])->find();

            $data['data']['mobile'] = $post['mobile'];
            $data['data']['name'] = $post['name'];
            $data['data']['head_portrait'] = C('AVATAR_URL') . $head_portrait;
            $data['data']['id'] = $friend['id'];
            $data['data']['relation_id'] = $myrst;
            $data['user_id'] = $post['user_id'];
            $data['type'] = 1;// 1添加好友关系

            $jpush = new \Vendor\Jpush\Jpush();

            if (!empty($user['deviceid_tv'])) {

                $rst = $jpush->push($user['deviceid_tv'], $data, '只是一个测试');

                if (!$rst) {
                    returnajax(false, '', '添加失败，请稍后再试(推送失败)');
                }
            }

            if (!empty($user['deviceid_mobile'])) {

                $rst = $jpush->push($user['deviceid_mobile'], $data, '只是一个测试');

                if (!$rst) {
                    returnajax(false, '', '添加失败，请稍后再试(推送失败)');
                }
            }

            cache($post['user_id'] . 'AmigosLists', NULL);
            returnajax(true, '', '添加成功');
        }
    }

    /**
     * 编辑关系
     * @author LiYang
     * @param  [relation_id]    关系ID
     * @param  [mobile]         手机号
     * @param  [name]           姓名
     * @date 2017-9-26
     * @return void
     */
    public function edit()
    {
        $data = [];
        $post = I('post.');

        $post['user_id'] = decode(base64_decode($post['user_id']));
        $post['relation_id'] = decode(base64_decode($post['relation_id']));

        if (empty($post['user_id'])) {
            returnajax(false, '', '用户ID不可为空');
        }

        if (empty($post['relation_id'])) {
            returnajax(false, '', '关系ID不可为空');
        }

        if (empty($post['name']) && empty($post['mobile'])) {
            returnajax(false, '', '备注名称和手机号不可同时为空');
        }

        $user = M('user')->where(['id' => $post['user_id']])->find();
        if (!$user) {
            returnajax(false, '', '该用户不存在');
        }

        $relation = M('relation')->where(['id' => $post['relation_id']])->find();

        if (!$relation) {
            returnajax(false, '', '该好友不存在');
        }

        $model = M('relation');
        $model->find('relation_id');
        $model->name = empty($post['name']) ? $model->name : $post['name'];

        if ($model->save() === false){
            returnajax(false, '', '编辑失败，请稍后再试');
        }

        $friend = M('user')->where(['mobile' => $post['mobile']])->find();
        $data['data']['mobile'] = $post['mobile'];
        $data['data']['name'] = $post['name'];
        $data['data']['relation_id'] = $post['relation_id'];
        $data['data']['id'] = $friend['id'];
        $data['user_id'] = $post['user_id'];
        $data['type'] = 2;// 1添加好友关系, 2编辑好友关系

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

    /**
     * 删除关系
     * @author LiYang
     * @param  [relation_id]   手机号
     * @param  [user_id]  自身ID
     * @date 2017-9-26
     * @return void
     */
    public function delete()
    {
        $post = getRequest();

        if (empty($post['relation_id'])) {
            returnajax(FALSE, '', '关系ID不可为空');
        }

        $relation = M('relation')->where(['id' => $post['relation_id'], 'user_id' => $post['user_id']])->limit(1)->save(['display' => 2]);
        M('call_log')->where(['relation_id' => $post['relation_id']])->delete();

        if ($relation !== false) {
            cache($post['user_id'] . 'AmigosLists', NULL);
            returnajax(true, '', '删除成功');
        } else {
            returnajax(FALSE, '', '删除失败，请稍后再试');
        }

    }

    /**
     * 关系列表
     * @author LiYang
     * @date 2017-9-26
     * @return void
     */
    public function lists()
    {
        $post = getRequest();

        if (empty(getRequest('vaule')['user_id'])) {
            returnajax('false', '', '请登录');
        }

//        if (!cache($post['user_id'] . 'AmigosLists')){
//            returnajax(true, cache($post['user_id'] . 'AmigosLists'), '');
//        }
//
        $sql = "SELECT u.id,  u.head_portrait, u.name, last_call_time as call_time, last_hours_length as hours_length, 
                last_answer as answer, last_receive as receive, r.name as user_name,r .mobile, r.id AS relation_id, r.num FROM `dbsp_relation` as r
                LEFT JOIN `dbsp_user` as u ON r.mobile = u .mobile
               /* LEFT JOIN `call_log` ON relation_id = relation.id*/
                WHERE r.user_id = {$post['user_id']} and r.display = 1 GROUP BY r.id ORDER BY num DESC";
        $relationRst = M()->query($sql);

        if (empty($relationRst)) {
            cache($post['user_id'] . 'AmigosLists', NULL);
            returnajax(true, $relationRst, '该用户还未添加联系人');
        }

        foreach ($relationRst as &$j) {
            $j['head_portrait'] = !empty($j['head_portrait']) ? C('AVATAR_URL') . $j['head_portrait'] : '';

            if ($j['user_name']) {
                $j['show'] = $j['user_name'];
                continue;
            }

            if ($j['name']) {
                $j['show'] = $j['name'];
                continue;
            }

            $j['show'] = $j['mobile'];
        }

//        $callLogRst = M('call_log')
//            ->where(['relation_id' =>['in',array_column($relationRst, 'relation_id')]])
//            ->select();
//
//        foreach ($relationRst as &$value) {
//            foreach ($callLogRst as $k => $v) {
//                if ($value['relation_id'] == $v['relation_id']) {
//                    $value['hours_length'] = empty($v['hours_length']) ? '' : $v['hours_length'];
//                    $value['call_time'] = empty($v['call_time']) ? '' : $v['call_time'];
//                }
//            }
//        }

        cache($post['user_id'] . 'AmigosLists', $relationRst);

        returnajax(true, $relationRst, '');
    }

    /**
     * 头像列表
     * @author LiYang
     * @date 2017-9-26
     * @return void
     */
    public function headList()
    {
        getRequest();
        $data = [
            '/Public/static/headportrait/Faces_grandpa.png',
            '/Public/static/headportrait/Faces_grandma.png',
            '/Public/static/headportrait/Faces_dad.png',
            '/Public/static/headportrait/Faces_mom.png',
            '/Public/static/headportrait/Faces_uncle.png',
            '/Public/static/headportrait/Faces_aunt.png',
            '/Public/static/headportrait/Faces_belderbrother.png',
            '/Public/static/headportrait/Faces_beldersister.png',
            '/Public/static/headportrait/Faces_youngerbrother.png',
            '/Public/static/headportrait/Faces_youngersister.png',
        ];
        returnajax(true, $data);
    }

    /**
     * 强制下线
     * @author LiYang
     * @date 2017-9-26
     * @return void
     */
    public function forcedDownline()
    {
        $post = getRequest();
        $rst = M('user')->where(['id' => $post['user_id']])->field('deviceid_tv')->find();

        if ($rst['deviceid_tv'] != $post['deviceid'] && !empty($rst['deviceid_tv'])) {
            returnajax('true', '', '该账号已在其他设备登陆', 9);
        }
    }
}