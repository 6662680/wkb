<?php

namespace Home\Model;
use Think\Model;
class PutorderModel extends Model
{

    Protected $autoCheckFields = false;

    //订单列表
    public function orderList($type, $commodity_type)
    {

        if ($type == 0) {
            $model = M('user_sell_order');
            if ($commodity_type == 1) {
                $model->join('left join `person_bag` on person_bag.id=user_sell_order.commodity_id');
                $model->join('left join `person` on person_bag.person_id=person.id');
                $model->join('left join `equipment_bag` on equipment_bag.id=user_sell_order.equipment_id');
                $model->join('left join `equipment` on equipment_bag.equipment_id=equipment.id');
                $model->join('left join `equipment_bag` as ebag on ebag.id=user_sell_order.equipment_id_card');
                $model->join('left join `equipment` as e on ebag.equipment_id=e.id');
                $model->field('user_sell_order.*,user_sell_order.id as order_id, person.*, equipment.equipment_img,
                e.equipment_img as equipment_img_card, ebag.equipment_endurance, e.equipment_name as equipment_name_card, equipment.equipment_multiple');

            } else {
                $model->join('left join `equipment_bag` on equipment_bag.id=user_sell_order.commodity_id');
                $model->join('left join `equipment` on equipment_bag.equipment_id=equipment.id');
                $model->field('user_sell_order.*,user_sell_order.id as order_id, equipment.*');
            }

            $model->where(['user_sell_order.status' => 1,'user_sell_order.commodity_type' => $commodity_type]);
            $model->order('user_sell_order.id desc');

            $rst  = $model->select();

        } else {
            $model = M('user_buy_order');
            if ($commodity_type == 1) {

                $model->join('left join `person` on user_buy_order.commodity_id=person.id');
                $model->field('user_buy_order.*,user_buy_order.id as order_id, person.*');
            } else {
                $model->join('left join `equipment` on user_buy_order.commodity_id=equipment.id');
                $model->field('user_buy_order.*,user_buy_order.id as order_id, equipment.*');
            }

            $rst = $model->where(['user_buy_order.status' => 1,'user_buy_order.commodity_type' => $commodity_type])->order('user_buy_order.id desc')->select();
        }

        return $rst;
    }

    //创建订单（订单类型：卖）
    public function sellCreationOrder($user_id, $commodity_id, $commodity_type, $commodity_price)
    {
        if (empty($user_id) || empty($commodity_id) || empty($commodity_type) || empty($commodity_price)) {
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
            unset($commodity['equipment_id']);
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
          'site' => 0,
          'equipment_id' => $commodity['equipment_id'] ? $commodity['equipment_id'] : 0,
          'equipment_id_card' => $commodity['equipment_id_card'] ? $commodity['equipment_id_card'] : 0,
          'equipment_name' => $equipment_name['equipment_name'] ? $equipment_name['equipment_name'] : 0,
          'equipment_card_name' => $equipment_card_name['equipment_name'] ? $equipment_card_name['equipment_name'] : 0,
        ];

        $rst = M('user_sell_order')->add($add);

        if ($rst) {
            $trans->commit();
            return ['status' => true];
        } else {
            $trans->rollback();
            return ['status' => false, 'msg' => '创建订单失败，请稍候再购买'];
        }
    }

    //用户接单（订单类型：卖）
    public function sellReceiving($receiving_user_id,$user_sell_order_id)
    {
        $rst = M('user')->where(['id' => $receiving_user_id])->field('site')->find();

        if (!$rst['site']) {
            return ['status' => false, 'msg' => '您没有设置您的玩客币地址，请先设置地址'];
        }

        $time = time() - C('ORDER_TIME');
        $time = array('GT', $time);
        //creation_time这里有问题，之后测试再调整
        $model = M('user_sell_order');
        $rst = $model->where(['receiving_user_id' => $receiving_user_id, 'use_time' => $time])->find();

        if ($rst) {
            return ['status' => false, 'msg' => '您有已锁定的订单，请先完成之前的交易'];
        }

        $rst = M('user_sell_order')->where(['id' => $user_sell_order_id])->find();

        if ($rst['user_id'] == $receiving_user_id) {
            return ['status' => false, 'msg' => '这是您自己下的单'];
        }

        if (!$rst) {
            return ['status' => false, 'msg' => '无法找到此订单'];
        }

        $map = array();
        $map['id'] = $user_sell_order_id;

        if (time() - $rst['use_time'] < C('ORDER_TIME')) {
            return ['status' => FALSE, 'msg' => '此订单正在被交易中'];
        }

        $userData = M('user')->where(['id' => $receiving_user_id])->find();

        $rst = M('user_sell_order')
            ->where(['id' => $user_sell_order_id])
            ->limit(1)
            ->save(['receiving_user_id' => $receiving_user_id, 'use_time' => time(), 'site' => $userData['site']]);

        if ($rst !== 1) {
            return ['status' => FALSE, 'msg' => '接单失败，请稍后再试'];
        }

        return ['status' => true];
    }

    // 完成购买（订单类型：卖）
    public function sellAccomplish($user_id, $order_id)
    {
        if (empty($user_id) || empty($order_id)) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        $userRst = M('user')->where(['id' => $user_id])->field('id')->find();

        if (!$userRst['id']) {
            return ['status' => false, 'msg' => '非法访问'];
        }

        //D('Log')->addLog('用户付款'. $commodity_price .', 地址为：'. $site, $userRst['user_id']);

        $time = time() - (C('ORDER_TIME') + 300);
        $time = array('GT', $time);
        //'creation_time' => $time,
        $orderRst = M('user_sell_order')->where(['id' => $order_id, 'status' => 1])->find();

        if (!$orderRst) {
//            D('Log')->addLog('交易异常：订单不存在或者过期失效', $userRst['id']);
            return ['status' => false, 'msg' => '订单不存在或者过期失效'];
        }

        $trans = new Model();
        $trans->startTrans();

        $buymsg = '购买道具:';
        $selllog = '卖出道具:';
        if ($orderRst['commodity_type'] == 1) {
            $saveRst = M('person_bag')->where(['id' => $orderRst['commodity_id']])->limit(1)->save(['user_id' => $userRst['id'], 'order_use' => 0]);

            if ($saveRst === false) {
                D('Log')->addLog('交易失败:用户付款'. $orderRst['commodity_price'] .', 该人物已在出售中或者不存在：', $userRst['id']);
                $trans->rollback();
                return ['status' => false, 'msg' => '道具交易失败1'];
            } else {
                $buymsg .= $orderRst['commodity_name'];
                $selllog .= $orderRst['commodity_name'];
            }

            if ($orderRst['equipment_id']) {
                $saveRst = M('equipment_bag')->where(['id' => $orderRst['equipment_id'], 'order_use' => 1])->limit(1)->save(['user_id' => $userRst['id'], 'order_use' => 0]);

//                pr(M()->getLastSql());
                if ($saveRst === false) {
                    $trans->rollback();
                    D('Log')->addLog('交易失败:用户付款'. $orderRst['commodity_price'] .', 该人物携带的装备已在出售中或者不存在：', $userRst['id']);
                    return ['status' => false, 'msg' => '道具交易失败2'];
                } else {
                    $buymsg .= ',附带装备:' .$orderRst['equipment_name'];
                    $selllog .= ',附带装备:' .$orderRst['equipment_name'];
                }
            }

            if ($orderRst['equipment_id_card']) {
                $saveRst = M('equipment_bag')->where(['id' => $orderRst['equipment_id_card'], 'order_use' => 1])->limit(1)->save(['user_id' => $userRst['id'], 'order_use' => 0]);

                if ($saveRst === false) {
                    $trans->rollback();
                    D('Log')->addLog('交易失败:用户付款'. $orderRst['commodity_price'] .', 该人物携带的帽子已在出售中或者不存在：', $userRst['id']);
                    return ['status' => false, 'msg' => '道具交易失败3'];
                } else {
                    $buymsg .= ',附带帽子:' .$orderRst['equipment_card_name'];
                    $selllog .= ',附带帽子:' .$orderRst['equipment_card_name'];
                }

            }
        }

        if ($orderRst['commodity_type'] == 2) {
            $saveRst = M('equipment_bag')->where(['id' => $orderRst['commodity_id']])->limit(1)->save(['user_id' => $userRst['id'], 'order_use' => 0]);

            if (!$saveRst === false) {
                D('Log')->addLog('交易失败:用户付款'. $orderRst['commodity_price'] .', 该装备已在出售中或者不存在：', $userRst['id']);
                $trans->rollback();
                return ['status' => false, 'msg' => '道具交易失败'];

            } else {
                $buymsg .= $orderRst['commodity_name'];
                $selllog .= $orderRst['commodity_name'];
            }
        }


        $model = M('user_sell_order');
        $saveRst = $model->where(['id' => $order_id, 'status' => 1])->save(['status' => 2,'completion_time' => time(), 'receiving_user_id' => $userRst['id']]);



        if ($saveRst === false) {
            $trans->rollback();
            D('Log')->addLog('交易异常：订单不存在或者过期失效', $userRst['user_id']);
            return ['status' => false, 'msg' => '没有找到该订单'];
        }

        //添加日志
        D('Log')->addLog($buymsg .', 一共价格为：'. $orderRst['commodity_price'], $userRst['id']);
        D('Log')->addLog($selllog.', 一共价格为：'. $orderRst['commodity_price'], $orderRst['user_id']);

        $trans->commit();
        return ['status' => true];
    }

    //创建订单（订单类型：买）
    public function buyCreationOrder($user_id, $data)
    {

        if (empty($user_id) || empty($data['commodity_id']) || empty($data['commodity_type']) || empty($data['commodity_price']) || empty($data['residue'])) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        if ($data['commodity_type'] != 1 && $data['commodity_type'] != 2) {
            return ['status' => false, 'msg' => '参数类型错误'];
        }

        //获取装备原始信息
        $model = M(C('COMMODITY_TYPE')[$data['commodity_type']]);
        $commodity_rst = $model->where(['id' => $data['commodity_id']])->find();

        $add = array();

        if ($data['commodity_type'] == 1) {
            if (empty($data['person_level'])) {
                return ['status' => false, 'msg' => '缺少参数'];
            }

            $add['commodity_name'] = $commodity_rst['person_name'];
            $add['person_level'] = $data['person_level'];
            $add['capacity'] = $data['capacity'];

        } else {
            $add['commodity_name'] = $commodity_rst['equipment_name'];
        }

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

        $add['commodity_id'] = $data['commodity_id'];
        $add['commodity_type'] = $data['commodity_type'];
        $add['status'] = 1;
        $add['creation_time'] = time();
        $add['user_id'] = $user_id;
        $add['commodity_price'] = $data['commodity_price'];
        $add['residue'] = $data['residue'];
        $add['site'] = 0;
		$add['capacity'] = $data['capacity'];//zh加的，之前购买道具时，效率写不进数据库

        $rst = M('user_buy_order')->add($add);

        if ($rst) {
            $trans->commit();
            return ['status' => true];
        } else {
            $trans->rollback();
            return ['status' => false, 'msg' => '创建订单失败，请稍候再购买'];
        }
    }

    //点击购买(订单类型：买)
    public  function buyReceiving($receiving_user_id, $user_buy_order_id)
    {
        $order = M('user_buy_order')->where(['id' => $user_buy_order_id])->find();

        if (!$order) {
            return ['status' => false, 'msg' => '不存在的订单'];
        }

//        if ($order['use'] == 1 && $order['use_time'] > time() - C('ORDER_TIME')) {
//            return ['status' => false, 'msg' => '订单正在交易'];
//        }

        $map = [
            'receiving_user_id' => $receiving_user_id,
            'use_time' => ['GT' => time() - C('ORDER_TIME')],
            'status' => 1,
        ];

        if (M('user_buy_order')->where($map)->find()) {
            return ['status' => false, 'msg' => '您有正在交易的订单，请先完成交易'];
        }

        if ($order['commodity_type'] == 1) {
            $map = [
                'level' => ['GT', $order['person_level']],
                'person_id' => $order['commodity_id'],
                'blood' => ['EGT', $order['residue']],
                'user_id' => $receiving_user_id,
            ];
            $find = M('person_bag')->where($map)->find();
        }

        if ($order['commodity_type'] == 2) {
            $map = [
                'equipment_id' => $order['commodity_id'],
                'equipment_endurance' => ['EGT', $order['residue']],
                'user_id' => $receiving_user_id,
            ];
            $find = M('equipment_bag')->where($map)->find();
        }

        if (!$find) {
            return ['status' => false, 'msg' => '您的背包中没有对应的道具'];
        }

        $user = M('user')->where(['id' => $order['user_id']])->find();

        if (empty($user['site'])) {
            return ['status' => false, 'msg' => '该用户没有设置玩客币地址，此订单无效'];
        }

        $receiving_user = $user = M('user')->where(['id' => $receiving_user_id])->find();

        $save = M('user_buy_order')
            ->where(['id' => $user_buy_order_id])
            ->limit(1)
            ->save(['use' => 1, 'use_time' => time(), 'receiving_user_id' => $receiving_user_id, 'site' => $receiving_user['site']]);

        if ($save !== 1) {
            return ['status' => false, 'msg' => '购买失败，请稍候再试'];
        } else {
            return ['status' => true, 'data' => ['server_charge' => C('SERVER_ADDRESS'), 'put_user' => $user['site']]];
        }
    }

    // 完成购买（订单类型：买）
    public function buyAccomplish($receiving_user_id, $user_buy_order_id)
    {
        $order = M('user_buy_order')->where(['id' => $user_buy_order_id])->find();

        if (!$order) {
            return ['status' => false, 'msg' => '不存在的订单'];
        }

        $buymsg = '购买道具:'. $order['commodity_name'];
        $selllog = '卖出道具:'. $order['commodity_name'];

        if ($order['commodity_type'] == 1) {
            $map = [
                'level' => ['GT', $order['person_level']],
                'person_id' => $order['commodity_id'],
                'user_id' => $receiving_user_id,
            ];
            $save = M('person_bag')->where($map)->limit(1)->save(['user_id' => $receiving_user_id]);
        }

        if ($order['commodity_type'] == 2) {
            $map = [
                'equipment_id' => $order['commodity_id'],
                'user_id' => $receiving_user_id,
            ];
            $save = M('equipment_bag')->where($map)->limit(1)->save(['user_id' => $receiving_user_id]);
        }
        $savedata = M('user_buy_order')->where(['id' => $user_buy_order_id])->save(['status' => 2]);

        if ($save === FALSE || $savedata ===FALSE) {
            return ['status' => false, 'msg' => '道具交易失败，请联系客服'];
        } else {
            //添加日志
            D('Log')->addLog($buymsg .', 一共价格为：'. $order['commodity_price'], $order['id']);
            D('Log')->addLog($selllog.', 一共价格为：'. $order['commodity_price'], $receiving_user_id);
            return ['status' => true];
        }

    }


}
