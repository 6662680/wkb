<?php

namespace Home\Model;
use Think\Model;
class PutorderModel extends Model
{

    Protected $autoCheckFields = false;

    //创建订单
    public function creationOrder($user_id, $commodity_id, $commodity_type, $commodity_price, $order_type)
    {
        if (empty($user_id) || empty($commodity_id) || empty($commodity_type) || empty($commodity_price) || empty($order_type)) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        if ($commodity_type != 1 && $commodity_type != 2) {

            return ['status' => false, 'msg' => '参数类型错误'];
        }

        //获取自身装备信息
        $model = M(C('COMMODITY_TYPE')[$commodity_type] . '_bag');
        $commodity_rst = $model->where(['id' => $commodity_id])->find();

        if (empty($commodity_rst)) {
            return ['status' => false, 'msg' => '不存在的道具'];
        }

        //获取自身玩客币地址
        $rst = M('user')->where(['id' => $user_id])->field('site')->find();

        if (!$rst['site']) {
            return ['status' => false, 'msg' => '您没有设置您的玩客币地址，请先设置地址'];
        }

        $trans = new Model();
        $trans->startTrans();

        if ($commodity_type == 1) {
            $commodity = M('person_bag')
                ->where(['person_bag.id' => $commodity_id])
                ->join('left join `person` on person_bag.person_id = person.id')
                ->find();
            $commodity_name = $commodity['person_name'];

            $saveRst = M('person_bag')->where(['id' => $commodity_id, 'order_use' => 0])->limit(1)->save(['order_use' => 1]);

            if ($saveRst != 1 ) {
                $trans->rollback();
                return ['status' => false, 'msg' => '该人物已在出售中或者不存在'];
            }

            if ($commodity['equipment_id']) {

                $equipment_name = M('equipment_bag')
                    ->where(['equipment_bag.id' => $commodity['equipment_id'], 'order_use' => 0])
                    ->join('left join `equipment` on equipment_bag.equipment_id = equipment.id')
                    ->field('equipment_name')
                    ->find();

                $saveRst = M('equipment_bag')
                    ->where(['id' => $commodity['equipment_id'], 'order_use' => 0])
                    ->limit(1)
                    ->save(['order_use' => 1]);

                if ($saveRst != 1) {
                    $trans->rollback();
                    return ['status' => false, 'msg' => '该人物携带的装备已在出售中或者不存在'];
                }
            }

            if ($commodity['equipment_id_card']) {

                $equipment_card_name = M('equipment_bag')
                    ->where(['equipment_bag.id' => $commodity['equipment_id_card'], 'order_use' => 0])
                    ->join('left join `equipment` on equipment_bag.equipment_id = equipment.id')
                    ->field('equipment_name')
                    ->find();

                $saveRst = M('equipment_bag')
                    ->where(['id' => $commodity['equipment_id_card'], 'order_use' => 0])
                    ->limit(1)
                    ->save(['order_use' => 1]);

                if ($saveRst != 1) {
                    $trans->rollback();
                    return ['status' => false, 'msg' => '该人物携带的帽子已在出售中或者不存在'];
                }
            }
        }

        if ($commodity_type == 2) {
            $commodity = M('equipment_bag')
                ->where(['equipment_bag.id' => $commodity_id])
                ->join('left join `equipment` on equipment_bag.equipment_id = equipment.id')
                ->find();
            $commodity_name = $commodity['equipment_name'];

            if ($commodity['equipment_id']) {
                $saveRst = M('equipment_bag')->where(['id' => $commodity['equipment_id'], 'order_use' => 0])->limit(1)->save(['order_use' => 1]);
                if ($saveRst != 1) {
                    $trans->rollback();
                    return ['status' => false, 'msg' => '该人物携带的装备已在出售中或者不存在'];
                }
            }
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
          'equipment_id' => $commodity['equipment_id'] ? $commodity['equipment_id'] : 0,
          'equipment_id_card' => $commodity['equipment_id_card'] ? $commodity['equipment_id_card'] : 0,
          'equipment_name' => $equipment_name['equipment_name'] ? $equipment_name['equipment_name'] : 0,
          'equipment_card_name' => $equipment_card_name['equipment_name'] ? $equipment_name['equipment_name'] : 0,
        ];

        $rst = M('user_order')->add($add);

        if ($rst) {
            $trans->commit();
            return ['status' => true];
        } else {
            $trans->rollback();
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
        if (empty($commodity_price) || empty($site)) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        $userRst = M('user')->where(['site' => $site])->field('id')->find();

        if (!$userRst['id']) {
            return ['status' => false, 'msg' => '付款成功，购买失败原因：找不到此玩客币地址'];
        }

        D('Log')->addLog('用户付款'. $commodity_price .', 地址为：'. $site, $userRst['user_id']);

        $time = time() - (C('ORDER_TIME') + 300);
        $time = array('GT', $time);
        //'creation_time' => $time,
        $orderRst = M('user_order')->where(['site' => $site, 'commodity_price' => $commodity_price,  'status' => 1])->find();

        if (!$orderRst) {
            D('Log')->addLog('交易异常：订单不存在或者过期失效', $userRst['id']);
            return ['status' => false, 'msg' => '订单不存在或者过期失效'];
        }

        $trans = new Model();
        $trans->startTrans();

        $logmsg = '购买道具:';

        if ($orderRst['commodity_type'] == 1) {
            $saveRst = M('person_bag')->where(['id' => $orderRst['commodity_id']])->limit(1)->save(['user_id' => $userRst['id'], 'order_use' => 0]);

            if ($saveRst != 1) {
                D('Log')->addLog('交易失败:用户付款'. $commodity_price .', 该人物已在出售中或者不存在：', $userRst['id']);
                $trans->rollback();
                return ['status' => false, 'msg' => '道具易主失败'];
            } else {
                $logmsg .= $orderRst['commodity_name'];
            }

            if ($orderRst['equipment_id']) {
                $saveRst = M('equipment_bag')->where(['id' => $orderRst['equipment_id'], 'order_use' => 1])->limit(1)->save(['user_id' => $userRst['id'], 'order_use' => 0]);
                if ($saveRst != 1) {
                    $trans->rollback();
                    D('Log')->addLog('交易失败:用户付款'. $commodity_price .', 该人物携带的装备已在出售中或者不存在：', $userRst['id']);
                    return ['status' => false, 'msg' => '道具易主失败'];
                } else {
                    $logmsg .= ',附带装备:' .$orderRst['equipment_name'];
                }
            }

            if ($orderRst['equipment_id_card']) {
                $saveRst = M('equipment_bag')->where(['id' => $orderRst['equipment_id_card'], 'order_use' => 1])->limit(1)->save(['user_id' => $userRst['id'], 'order_use' => 0]);
                if ($saveRst != 1) {
                    $trans->rollback();
                    D('Log')->addLog('交易失败:用户付款'. $commodity_price .', 该人物携带的帽子已在出售中或者不存在：', $userRst['id']);
                    return ['status' => false, 'msg' => '道具易主失败'];
                } else {
                    $logmsg .= ',附带帽子:' .$orderRst['equipment_card_name'];
                }

            }
        }

        if ($orderRst['commodity_type'] == 2) {
            $saveRst = M('equipment_bag')->where(['id' => $orderRst['commodity_id']])->limit(1)->save(['user_id' => $userRst['id'], 'order_use' => 0]);

            if (!$saveRst != 1) {
                D('Log')->addLog('交易失败:用户付款'. $commodity_price .', 该装备已在出售中或者不存在：', $userRst['id']);
                $trans->rollback();
                return ['status' => false, 'msg' => '道具易主失败'];

            } else {
                $logmsg .= $orderRst['commodity_name'];
            }
        }


        $model = M('user_order');
        $saveRst = $model->where(['site' => $site, 'commodity_price' => $commodity_price, 'creation_time' => $time, 'status' => 1])->save(['status' => 2]);

        if ($saveRst != 1) {
            $trans->rollback();
            D('Log')->addLog('交易异常：订单不存在或者过期失效', $userRst['user_id']);
            return ['status' => false, 'msg' => '没有找到该订单'];
        }
        
        //添加日志
        D('Log')->addLog($logmsg .', 一共价格为：'. $orderRst['commodity_price'], $userRst['user_id']);

        $trans->commit();
        return ['status' => true];
    }
}
