<?php

namespace Home\Model;
use Think\Model;
class UserModel extends Model
{

    //获取会员
    public function getUser()
    {
        $user = M('user')
            ->where(['id' => session('user_id')])
            ->find();

        if ($user) {
            return ['status' => true, 'data' => $user];
        } else {
            return ['status' => false, 'msg' => '数据库中没有该会员'];
        }

    }


}
