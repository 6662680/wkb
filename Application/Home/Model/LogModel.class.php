<?php

namespace Home\Model;
use Think\Model;
class LogModel extends Model
{
    Protected $autoCheckFields = false;
    public function addLog($msg, $uid = NULL)
    {
        if (session('user_id')) {
            $user_id = session('user_id');
        }

        if ($uid) {
            $user_id = $uid;
        }

        if (!$user_id) {
            returnajax('false', '', '写入日志失败', 2);
        }

        M('user_log')->add(['user_id' => $user_id, 'time' => time(), 'msg' => $msg]);
    }

}
