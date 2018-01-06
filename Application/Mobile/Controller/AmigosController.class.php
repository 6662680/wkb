<?php
namespace Mobile\Controller;
use Think\Controller;
use JPush\Client as JPush;
class AmigosController extends Controller

{
    public function __construct()
	{
        parent::__construct();
	}

    /**
     * 查找联系人
     * @author LiYang
     * @param  [mobile]   手机号
     * @param  [user_id]   自身ID
     * @date 2017-11-13
     * @return void
     */
    public function find()
    {
        $post = getRequest();

        if (empty($post['mobile'])) {
            returnajax(FALSE, '', '手机号不能为空');
        }

        $friend = M('user')
            ->where(['mobile' => $post['mobile']])
            ->field('username, user.mobile, user.id, user.head_portrait')
            ->find();


        $friend['head_portrait'] = $friend['head_portrait'] ? C('AVATAR_URL') . $friend['head_portrait'] : '';


       $count = M('relation')->where(['mobile' => $post['mobile'], 'user_id' => $post['user_id'], 'display' => 1])->count();

        if ($count > 0) {
            $friend['friend'] = true;
        } else {
            $friend['friend'] = false;
        }

        returnajax(true, $friend, '');
    }

    /**
     * 添加关系
     * @author LiYang
     * @param  [user_id]  自身ID
     * @param  [mobile]   手机号
     * @date 2017-9-26
     * @return void
     */
    public function add()
    {
        $data = [];
        $post = getRequest();

        if (empty($post['user_id'])) {
            returnajax(FALSE, '', '缺少参数');
        }

        if (empty($post['mobile'])) {
            returnajax(FALSE, '', '手机号不能为空');
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

        $rst = M('relation')->where(['user_id' => $post['user_id'], 'mobile' => $post['mobile']])->find();

        if ($rst && $rst['display'] == 1) {
            returnajax(FALSE, '', '已存在的好友');
        }

        if ($rst && $rst['display'] == 2) {
            $rst = M('relation')->where(['user_id' => $post['user_id'], 'mobile' => $post['mobile']])->save(['display' => 1]);

            if ($rst !== false) {
                $result = true;
            } else {
                $result = false;
            }

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
            $model->id = NULL;
            $model->user_id = $post['user_id'];
            $model->mobile = $post['mobile'];
            $model->name = '';
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

            if (!empty($user['deviceid_mobile'])) {
                $data['data']['mobile'] = $post['mobile'];
                $data['data']['name'] = $post['name'];
                $data['data']['head_portrait'] = C('AVATAR_URL')  . $head_portrait;
                $data['data']['id'] = $friend['id'];
                $data['data']['relation_id'] = $rst;
                $data['user_id'] = $post['user_id'];
                $data['type'] = 1;// 1添加好友关系

                $jpush = new \Vendor\Jpush\Jpush();
                $rst = $jpush->push($user['deviceid_mobile'], $data, '只是一个测试');

                if (!$rst) {
                    returnajax(false, '', '添加成功，但通知电视出现了异常，请关闭电视APP重新登录');
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
        $post = getRequest();

        if (empty($post['user_id'])) {
            returnajax(false, '', '缺少参数');
        }

        if (empty($post['relation_id'])) {
            returnajax(false, '', '关系ID不可为空');
        }

        if (empty($post['name']) && empty($post['mobile'])) {
            returnajax(false, '', '备注名称和手机号不可同时为空');
        }

        $user = M('user')->where(['id' => $post['user_id']])->find();
        $relation = M('relation')->where(['id' => $post['relation_id']])->find();

        if (!$user || !$relation) {
            returnajax(false, '', '该用户不存在');
        }

        $model = M('relation');
        $model->find('relation_id');
        $model->name = empty($post['name']) ? $model->name : $post['name'];
//        $model->mobile = empty($post['mobile']) ? $model->mobile : $post['mobile'];

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
        $rst = $jpush->push($user['deviceid_tv'], $data, '只是一个测试');

        if ($rst) {
            cache($post['user_id'] . 'AmigosLists', NULL);
            returnajax(true, '', '编辑成功');
        } else {
            returnajax(false, '', '编辑失败，请稍后再试(推送失败)');
        }

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

//        if (!empty(cache($post['user_id'] . 'AmigosLists'))){
//            returnajax(true, cache($post['user_id'] . 'AmigosLists'), '');
//        }

        $sql = "SELECT `dbsp_user`.id, `dbsp_user`.head_portrait, `dbsp_user`.name as user_name, dbsp_relation.name, dbsp_relation.mobile, 
                dbsp_relation.id AS relation_id, count(*) as count FROM `dbsp_user`
                LEFT JOIN `dbsp_relation` ON dbsp_relation.mobile = `dbsp_user` .mobile
                WHERE `user_id` = {$post['user_id']} and dbsp_relation.display = 1 GROUP BY dbsp_relation.id ORDER BY count DESC";
        $relationRst = M()->query($sql);

        if (empty($relationRst)) {
            returnajax(true, $relationRst, '该用户还未添加联系人');
        }

        foreach ($relationRst as &$j) {

            if (!empty($j['head_portrait'])) {
                $j['head_portrait'] = C('AVATAR_URL') . $j['head_portrait'];
            }

            if ($j['name']) {
                $j['show'] = $j['name'];
                continue;
            }

            if ($j['user_name']) {
                $j['show'] = $j['user_name'];
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
     * 匹配数据
     * @author LiYang
     * @param  [user_id]  自身ID
     * @param  [type]   类型:1未开通，2已开通
     * @param  [lists]  用户匹配列表
     * @date 2017-10-26
     * @return void
     */
    public function matching ()
    {
        $post = getRequest();
        $rst = [];

//        $post['type'] = 2;
//        $post['lists'] = [
//            '15068859103',
//            '13588835122',
//            '18679354419'
//        ];
//        $post['user_id'] =  82;

        if (is_array($post['lists']) && empty($post['type']) && empty(getRequest('vaule')['user_id'])) {
            returnajax(false, '', '参数不正确');
        }

        $relation_rst = M('user')
            ->where(['mobile' => ['in', $post['lists']]])
            ->field('mobile, id')
            ->select();

        $post['lists'] = explode(',', $post['lists']);

        if ($post['type'] == 1) {
            /*判断差集*/
            $difference = array_diff($post['lists'], array_column($relation_rst, 'mobile'));
//            /*查找联系人*/
//          $userRst = M('user')->where(['mobile' => ['in', implode(',' ,$rst)]])->field('mobile, id')->select();


            /*查找自身*/
            $user = M('user')->where(['id' => $post['user_id']])->field('mobile')->find();
            /*查找日志*/
            $log = M('send_log')
                ->where(['type' => 2, 'mobile' => $user['mobile'], 'user_mobile' => ['in', $post['lists']]])
                ->select();

            //判断是否邀请
            $i = -1;
            foreach ($difference as $key => &$value) {
                $i++;
                $rst[$i]= ['id' => $key, 'mobile' => $value];

                foreach ($log as $v) {
                    if ($value == $v['user_mobile']) {
                        $rst[$i]['invite'] = true;
                    }
                }
            }

        }

        if ($post['type'] == 2) {

            //判断交集
            foreach ($post['lists'] as $value) {
                foreach ( $relation_rst as $v) {
                    if ($value == $v['mobile']) {
                        $rst[] = $v;
                    }
                }
            }

            /*查询是否是好友*/
            $relation = M('relation')
                ->where(
                    [
                     'mobile' => ['in', array_column($rst,'mobile')],
                     'user_id' => $post['user_id'],
                     'display' => 1
                    ]
                )->select();

            //判断是否是好友
            foreach ($rst as $key => $arr) {
                foreach ($relation as $v) {
                    if ($arr['mobile'] == $v['mobile']) {
                        $rst[$key]['friend'] = true;
                    }
                }
            }
        }

        returnajax('true', $rst, '');
    }

}