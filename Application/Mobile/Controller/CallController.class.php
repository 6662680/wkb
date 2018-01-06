<?php
namespace Mobile\Controller;

class CallController extends BaseController
{
    public function __construct()
	{
        parent::__construct();
	}
    /**
     * 通话结束后上传通话时间
     * @author LiYang
     * @param  [relation_id]   关系ID
     * @param  [hours_length] 通话时长
     * @param  [call_time] 通话时间
     * @param  [receive] 是否接听 1接听 2未接通
     * @param  [mobile] 对方手机号
     * @param  [user_mobile] 自身手机号
     * @param  [user_id] 自身id
     * @param  [friends_id] 好友id
     * @param  [answer] 被叫还是主叫： 1主叫，2被叫
     * @date 2017-9-23 09:00:00
     * @return void
     */
    public function callTime()
    {
        $post = getRequest();
//
//        if (!isset($post['hours_length']) || !isset($post['call_time']) || !isset($post['mobile']) || !isset($post['user_mobile']) || !isset($post['receive']) || !isset($post['answer'])) {
//            returnajax(false, '', '缺少参数');
//        }

        $data = $post;

        if ($post['answer'] == 2) {
            $relation = M('relation')->where(['user_id' => $post['user_id'], 'mobile' => $post['mobile']])->find();
            $post['relation_id'] = $data['relation_id'] = $relation['id'];

            if ($relation['display'] == 2) {
                M('relation')->where(['id' => $post['relation_id']])->save(['display' => 1]);
            }

            if (empty($relation)) {
                $model = M('relation');
                $model->user_id = $post['user_id'];
                $model->mobile = $post['mobile'];
                $model->name = '';
                $model->display = 1;
                $model->add();
            }

        }

        $rst = M('call_log')->add($data);

        $data = [
            'last_call_time' => $post['call_time'],
            'last_hours_length' => $post['hours_length'],
            'last_answer' => $post['answer'],
            'last_receive' => $post['receive']
        ];

        $upRst = M('relation')->
        where(['id' => $post['relation_id']])
            ->save($data);

        if ($rst && $upRst !== false) {
            cache($post['user_id'] . 'AmigosLists', NULL);
            returnajax(true, '', '上传成功');
        } else {
            returnajax(false, '', '上传失败');
        }
    }

    /**
     * 发送短信通知好友
     * @author LiYang
     * @param  [user_mobile]   自身手机号
     * @param  [mobile]   手机号
     * @param  [type]   类型:1邀请上线，2邀请注册
     * @date 2017-9-23 09:00:00
     * @return void
     */
    public function send()
    {
        $receive = 5; //接受者只能一天收到5条短信
        date_default_timezone_set('PRC');
        $dayBegin = strtotime(date('Y-m-d', time()));
        $dayEnd = $dayBegin + 24 * 60 * 60;

        $post = getRequest();

        if (empty($post['mobile']) && empty($post['user_mobile'])) {
            returnajax(false, '', '请输入手机号');
        }

        if (empty($post['type'])) {
            returnajax(false, '', '参数type缺少');
        }

        /*如果是邀请上线，那么判断今天是否邀请,如果是邀请注册，那么只能邀请一次*/
        $condition = [];
        if ($post['type'] == 1) {
            $condition['time'] = [['egt', $dayBegin], ['elt', $dayEnd], 'and' ];
            $condition['_logic'] = 'AND';
        }
        $condition['mobile'] =  $post['mobile'];
        $condition['user_mobile'] =  $post['user_mobile'];
        $condition['type'] =  $post['type'];
        $rst = M('send_log')->where($condition)->count();

        if ($rst >= 1) {
            returnajax(false, '', '用户已经被邀请过了');
        }

        /*判断用户接收是否超过5条，超过则停止发送*/
        $condition = [];
        $condition['time'] = [['egt', $dayBegin], ['elt', $dayEnd], 'and' ];
        $condition['_logic'] = 'AND';
        $condition['user_mobile'] =  $post['user_mobile'];
        $rst = M('send_log')->where($condition)->count();

        if ($rst >= $receive) {
            returnajax(false, '', '用户今日被发送次数过多,请明日再邀请');
        }

        Vendor('AliyunMns.mns');

        if ($post['type'] == 1) {
            $sms = 'SMS_105340010';
        }

        if ($post['type'] == 2) {
            $sms = 'SMS_109445010';
        }
        $result = run($post['mobile'],  $sms, ['name' => $post['user_mobile']]);

        $data = [
            'type' => 1,
            'mobile' => $post['mobile'],
            'user_mobile' => $post['user_mobile'],
            'time' => time(),
        ];

        M('send_log')->add($data);

        if ($result){
            returnajax(true, '', '发送成功');
        } else {
            returnajax(false, '', '发送失败');
        }
    }

    /**
     * 通话记录
     * @author LiYang
     * @param  [user_mobile]    自身手机号
     * @param  [page]           页码
     * @param  [mobile]         手机号
     * @date 2017-9-23 09:00:00
     * @return void
     */
    public function log()
    {
        $post = getRequest();

        if (!isset($post['page'])) {
            returnajax(false,'', '缺少参数page');
        }

//        if ($relationRst = cache($post['user_id'] . 'log' . $post['page'])) {
//            returnajax('true', $relationRst, '');
//        }

        if ($post['page'] != 0) {
            $post['page'] = $post['page'] * 50;
        }

        $sql = "SELECT u.id, c.id as log_id, u.head_portrait, r.name as user_name, r.mobile, r.last_call_time as call_time,
                r.last_hours_length as hours_length, r.last_answer as answer,r.last_receive as receive, 
                r.id AS relation_id, u.name, count(*) as count 
                FROM `dbsp_call_log` as c
                LEFT JOIN `dbsp_relation` as r ON r.id = c.relation_id
                LEFT JOIN `dbsp_user` as u ON r.mobile = u .mobile
                WHERE `user_id` = {$post['user_id']} GROUP BY r.id ORDER BY log_id limit {$post['page']}, 50";
        $relationRst = M()->query($sql);

        $log_ids =  [];
        foreach ($relationRst as &$value) {
            $value['answer'] = (int)$value['answer'];
            $value['receive'] = (int)$value['receive'];
            $value['count'] = (int)$value['count'];
            $value['call_time'] = (int)$value['call_time'];
            $value['hours_length'] = (int)$value['hours_length'];
            $value['log_id'] = (int)$value['log_id'];
            $value['head_portrait'] =  !empty($value['head_portrait']) ? C('AVATAR_URL')  . $value['head_portrait'] : '';
            $log_ids[] = $value['log_id'];

            if ($value['user_name']) {
                $value['show'] = $value['user_name'];
                continue;
            }

            if ($value['name']) {
                $value['show'] = $value['name'];
                continue;
            }

            $value['show'] = $value['mobile'];
        }

        cache($post['user_id'] . 'log' . $post['page'], $relationRst);

        returnajax('true', $relationRst, '');
    }

    /**
     * 通话记录
     * @author LiYang
     * @param  [user_mobile]    自身手机号
     * @param  [page]           页码
     * @param  [mobile]         手机号
     * @date 2017-9-23 09:00:00
     * @return void
     */
    public function lognew()
    {
        $post = getRequest();

        if (!isset($post['page'])) {
            returnajax(false,'', '缺少参数page');
        }

        if ($post['page'] != 0) {
            $post['page'] = $post['page'] * 50;
        }

        $sql = "select c.id, a.id as log_id, c.head_portrait, b.name as user_name, b.mobile, b.last_call_time as call_time,
                b.last_hours_length as hours_length, b.last_answer as answer, b.last_receive as receive,
                b.id AS relation_id, c.name, count(a.id) as count 
                FROM `dbsp_call_log` a
                right JOIN `dbsp_relation` b ON a.user_id = b.user_id
                right join `dbsp_user` c ON b.mobile = c.mobile
				right JOIN `dbsp_relation` as re1 ON a.mobile = re1.mobile and a.friends_id=re1.user_id
                WHERE a.user_id = {$post['user_id']}  ORDER BY a.id limit 0, 50";

        $relationRst = M()->query($sql);

        $log_ids =  [];
        foreach ($relationRst as &$value) {
            $value['answer'] = (int)$value['answer'];
            $value['receive'] = (int)$value['receive'];
            $value['count'] = (int)$value['count'];
            $value['call_time'] = (int)$value['call_time'];
            $value['hours_length'] = (int)$value['hours_length'];
            $value['log_id'] = (int)$value['log_id'];
            $value['head_portrait'] =  'http://' . $_SERVER['HTTP_HOST'] . $value['head_portrait'];
            $log_ids[] = $value['log_id'];

            if (!empty($value['head_portrait'])) {
                $value['head_portrait'] = C('AVATAR_URL') . $value['head_portrait'];
            }

            if ($value['name']) {
                $value['show'] = $value['name'];
                continue;
            }

            if ($value['user_name']) {
                $value['show'] = $value['user_name'];
                continue;
            }

            $value['show'] = $value['mobile'];
        }

        cache($post['user_id'].'log', $relationRst);

        returnajax('true', $relationRst, '');
    }


    /**
     * 通话记录详情
     * @author LiYang
     * @param  [relation_id]   关系ID
     * @date 2017-9-23 09:00:00
     * @return void
     */
    public function logDetail()
    {
        $post = getRequest();

        if (!isset($post['relation_id'])) {
            returnajax(false,'', '缺少参数relation_id');
        }

       $rst = M('call_log')
           ->where(['relation_id' => $post['relation_id']])
           ->order('id desc')
           ->field('id as log_id, hours_length, call_time, receive, answer')
           ->limit(5)
           ->select();

        returnajax('true', $rst, '');
    }

    /**
     * 删除通话记录
     * @author LiYang
     * @param  [relation_id]   关系ID
     * @param  [log_id] 日志ID
     * @date 2017-9-23 09:00:00
     * @return void
     */
    public function delLog()
    {
        $post = getRequest();
        $data= [];

        if (empty($post['relation_id'])) {
            returnajax(false, '', '缺少参数');
        }

        if (!empty($post['log_id'])) {
            $data['id'] = $post['log_id'];
        }

        $data['relation_id'] = $post['relation_id'];
        $rst = M('call_log')->where($data)->delete();

        if ($rst) {
            cache($post['user_id'].'log', NULL);
            returnajax(true, '', '删除成功');
        } else {
            returnajax(false, '', '删除失败');
        }
    }

    /**
     * 验证是否被踢下线
     * @author LiYang
     * @param  [user_id]    自身id
     * @date 2017-9-23 09:00:00
     * @return void
     */
    public function validation()
    {
        $post = getRequest();

        if (!$post['user_id'] || !$post['deviceid']) {
            returnajax(false,  '非法请求', '');
        }

        $rst = M('user')->where(['id' => $post['user_id']])->field('deviceid_mobile')->find();

        if ($rst['deviceid_mobile'] != $post['deviceid'] && !empty($rst['deviceid_mobile'])) {
            returnajax(true,  ['offline' => true], '该账号已在其他设备登陆，若不是您的操作，请立刻修改密码',9);
        } else {
            returnajax(true,  ['offline' => true], '');
        }
    }

    public function test() {

    }

}