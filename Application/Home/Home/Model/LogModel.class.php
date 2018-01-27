<?php

namespace Home\Model;
use Think\Model;
class LogModel extends Model
{
    Protected $autoCheckFields = false;
    public function addLog($msg, $uid = NULL, $commodity_id =NULL, $commodity_type=NULL)
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


        M('user_log')->add(['user_id' => $user_id, 'time' => time(), 'msg' => $msg, 'commodity_type' => $commodity_type, 'commodity_id' => $commodity_id]);
    }

    public function findLog($id, $commodity_id =NULL, $commodity_type=NULL, $page = NULL)
    {
        $model = M('user_log');

        if ($commodity_id && $commodity_type) {
            $model->where(['user_id' => $id, 'commodity_id' => $commodity_id, 'commodity_type' => $commodity_type]);
        } else {
            $model->where(['user_id' => $id]);
        }

        if ($page) {
            $model->limit(10);
        }

        return $model->select();

    }

    public function getconfig($key, $value=NULL)
    {
        if ($value == null) {
            $rst = M('config')->where(['key' => $key])->find();
            return $rst['value'];
        } else {
            return M('config')->where(['key' => $key])->save(['value' => $value]);
        }

    }
}
