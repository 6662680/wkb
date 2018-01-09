<?php

namespace Home\Model;
use Think\Model;
class PutorderModel extends Model
{

    Protected $autoCheckFields = false;
    /**
     * 下单
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function putOrder($user_id, $commodity_id, $commodity_type, $commodity_price,$order_type)
    {
        if (empty($commodity_id) || empty($commodity_type) ) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        if ($commodity_type != 1 && $commodity_type != 2) {

            return ['status' => false, 'msg' => '参数类型错误'];
        }

        $model = M(C('COMMODITY_TYPE')[$commodity_type] . '_bag');
        $rst = $model->where(['id' => $commodity_id])->find();

        if (empty($rst)) {
            return ['status' => false, 'msg' => '不存在的道具'];
        }

        return $this->creationOrder($user_id, $commodity_id, $commodity_type, $commodity_price, $order_type);
    }


    //创建订单
    public function creationOrder($user_id, $commodity_id, $commodity_type, $commodity_price, $order_type)
    {
        if (empty($user_id) || empty($commodity_id) || empty($commodity_type) || empty($commodity_price) || empty($order_type)) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        $rst = M('user')->where(['id' => $user_id])->field('site')->find();

        if (!$rst['site']) {
            return ['status' => false, 'msg' => '您没有设置您的玩客币地址，请先设置地址'];
        }

        if ($commodity_type == 1) {
            $commodity = M('person_bag')
                ->where(['person_bag.id' => $commodity_id])
                ->join('left join `person` on person_bag.person_id = person.id')
                ->find();

            $commodity_name = $commodity['person_name'];
        }

        if ($commodity_type == 2) {
            $commodity = M('equipment_bag')
                ->where(['equipment_bag.id' => $commodity_id])
                ->join('left join `equipment` on equipment_bag.equipment_id = equipment.id')
                ->find();

            $commodity_name = $commodity['equipment_name'];
        }


        $add  = [
          'commodity_id' => $commodity_id,
          'commodity_type' => $commodity_type,
          'commodity_name' => $commodity_name,
          'status' => 1,
          'creation_time' => time(),
          'user_id' => $user_id,
          'commodity_price' => $commodity_price,
          'type' => $order_type,
          'site' => $rst['site'],
        ];

        $rst = M('user_order')->add($add);

        if ($rst) {
            return ['status' => true];
        } else {
            return ['status' => false, 'msg' => '创建订单失败，请稍候再购买'];
        }
    }

    //点击购买订单
    public function buy($user_id, $user_order_id)
    {
        $rst = M('user')->where(['id' => $user_id])->field('site')->find();

        if ($rst['site']) {
            return ['status' => false, 'msg' => '您没有设置您的玩客币地址，请先设置地址'];
        }

        $model = M('user_order');
        $rst = $model->where(['id' => $user_order_id])->find();

        if (!$rst) {
            return ['status' => false, 'msg' => '无法找到此订单'];
        }

        if ($rst['use'] == 1) {
            return ['status' => false, 'msg' => '订单正在交易中，无法交易'];
        }

        $time = time() - C('ORDER_TIME');
        $time = array('GT', $time);

        $model = M('user_order');
        $rst = $model->where(['user_id' => $user_id, 'creation_time' => $time])->find();

        if ($rst) {
            return ['status' => false, 'msg' => '您有已锁定的订单，请先完成之前的交易'];
        }

        $rst = $model->where(['id' => $user_order_id])->save(['use' => 1]);

        if ($rst === false) {
            return ['status' => false, 'msg' => '订单锁定失败，请稍后再购买'];
        }

        return ['status' => true];

    }

    // 完成购买
    public function accomplishBuy($commodity_price, $site)
    {
        if (empty($commodity_type) || empty($site)) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        if ($commodity_type != 1 && $commodity_type != 2) {
            return ['status' => false, 'msg' => '参数类型错误'];
        }

        $userRst = M('user')->where(['site' => $site])->field('user_id')->find();

        if (!$userRst['user_id']) {
            return ['status' => false, 'msg' => '付款成功，购买失败原因：找不到此玩客币地址'];
        }

        D('Log')->addLog('用户付款'. $commodity_price .', 地址为：'. $site, $userRst['user_id']);

        $time = time() - (C('ORDER_TIME') + 300);
        $time = array('GT', $time);

        $orderRst = M('user_order')->where(['site' => $site, 'commodity_price' => $commodity_price, 'creation_time' => $time, 'status' => 1])->find();

        if (!$orderRst) {
            D('Log')->addLog('交易异常：订单不存在或者过期失效', $userRst['user_id']);
            return ['status' => false, 'msg' => '订单不存在或者过期失效'];
        }

        $trans = new Model();
        $trans->startTrans();

        if ($commodity_type == 1) {
            $saveRst = M('person_bag')->where(['id' => $orderRst['commodity_id']])->save(['user_id' => $userRst['user_id']]);
        }

        if ($commodity_type == 2) {
            $saveRst = M('equipment_bag')->where(['id' => $orderRst['commodity_id']])->save(['user_id' => $userRst['user_id']]);;
        }

        if (!$saveRst) {
            D('Log')->addLog('交易异常：道具易主失败', $userRst['user_id']);
            $trans->rollback();
            return ['status' => false, 'msg' => '道具易主失败'];

        }
        $model = M('user_order');
        $saveRst = $model->where(['site' => $site, 'commodity_price' => $commodity_price, 'creation_time' => $time, 'status' => 1])->save(['status' => 2]);

        if ($saveRst === false) {
            $trans->rollback();
            D('Log')->addLog('交易异常：订单不存在或者过期失效', $userRst['user_id']);
            return ['status' => false, 'msg' => '没有找到该订单'];
        }
        
        //添加日志
        D('Log')->addLog('购买道具'. $orderRst['commodity_name'] .', 价格为：'. $orderRst['commodity_price'], $userRst['user_id']);

        $trans->commit();
        return ['status' => true];
    }
}
