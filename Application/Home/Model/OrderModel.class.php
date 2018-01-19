<?php

namespace Home\Model;
use Think\Model;
class OrderModel extends Model
{


    /**
     * 检测玩客币地址与商品是否存在
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function buy($user_id, $commodity_id, $commodity_type)
    {

        if (empty($commodity_id) || empty($commodity_type) ) {
            returnajax('fasle', '', '缺少参数');
        }

        if (!C('COMMODITY_TYPE')[$commodity_type]) {
            returnajax('fasle', '', '参数类型错误');
        }
		//检查玩客币地址，返回true
		$rst = M('user')->where(['id' => $user_id])->field('site')->find();

        if (!$rst['site']) {
            return ['status' => false, 'msg' => '您没有支付注册费,无法购买人物,请到个人中心完成支付!'];
        }
		
        $rst = M(C('COMMODITY_TYPE')[$commodity_type])->where(['id' => $commodity_id, 'status' => 0])->find();

        if (empty($rst)) {
            returnajax('fasle', '', '不存在的商品');
        }
		returnajax('true');

    }
	

    //创建订单
    public function creationOrder($user_id, $commodity_id, $commodity_type)
    {
        if (empty($user_id) || empty($commodity_id) || empty($commodity_type)) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        $rst = M('user')->where(['id' => $user_id])->field('site')->find();

        if (!$rst['site']) {
            return ['status' => false, 'msg' => '您没有支付注册费,无法购买人物,请到个人中心完成支付!'];
        }
		//创建订单
		$price= M(C('COMMODITY_TYPE')[$commodity_type])->where(['id' => $commodity_id, 'status' => 0])->find();
		$commodity_price=$price[C('COMMODITY_TYPE')[$commodity_type].'_price'];
        $add  = [
            'commodity_id' => $commodity_id,
            'commodity_type' => $commodity_type,
            'status' => 1,
            'creation_time' => time(),
            'user_id' => $user_id,
            'commodity_price' => $commodity_price,
            'site' => $rst['site'],
        ];
        $rst = M('order')->add($add);
		
        if ($rst) {
            return ['status' => true,'msg'=> $add['creation_time']];
        } else {
            return ['status' => false, 'msg' => '创建订单失败，请稍候再购买'];
        }
    }

    // 完成购买
    public function accomplishBuy($order_id)
    {
        if ( empty($order_id)) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        $orderRst = M('order')->where(['id' => $order_id])->find();
        $user = M('user')->where(['id' => session('user_id')])->find();
        if (!$orderRst['user_id']) {
            return ['status' => false, 'msg' => '付款成功，购买失败原因：找不到此玩客币地址'];
        }
		
        $rst = M(C('COMMODITY_TYPE')[$orderRst['commodity_type']])->where(['id' => $orderRst['commodity_id']])->find();

        if (!$rst) {
            return ['status' => false, 'msg' => '不存在的商品'];
        }

        $trans = new Model();
        $trans->startTrans();

        if ($orderRst['commodity_type'] == 1) {

            $model = M('person_bag');
            $model->user_id = $orderRst['user_id'];
            $model->person_id = $orderRst['commodity_id'];
            $model->equipment_id = 0;
            $model->equipment_id_card = 0;
            $model->blood = $rst['person_blood'];
            $model->level = 1;
            $model->yesterday_capacity = 0;
            $model->start_mining = 0;
            $addRst = $model->add();

            $commodity_name = $rst['person_name'];

            $commodity_price = $rst['person_price'];
        }

        if ($orderRst['commodity_type'] == 2) {
        	
            $model = M('equipment_bag');
            $model->user_id = $orderRst['user_id'];
            $model->equipment_id = $orderRst['commodity_id'];
            $model->equipment_endurance = $rst['equipment_endurance'];
            $model->person_id = 0;
            $model->use = 0;
            $addRst = $model->add();

            $commodity_name = $rst['equipment_name'];
            $commodity_price = $rst['equipment_price'];
        }

        if ($orderRst['commodity_type'] == 3) {

            $mediche_bag_rst = M('mediche_bag')
                ->where(['user_id' => $orderRst['user_id'], 'mediche_id' => $orderRst['commodity_id']])
                ->find();

            if ($mediche_bag_rst) {
                $addRst = M('mediche_bag')
                    ->where(['user_id' => $orderRst['user_id'], 'mediche_id' => $orderRst['commodity_id']])
                    ->setInc('mediche_num',1);
            } else {
                $model = M('mediche_bag');
                $model->user_id = $orderRst['user_id'];
                $model->mediche_id = $orderRst['commodity_id'];
                $model->mediche_num = 1;
                $addRst = $model->add();
            }

            $commodity_name = $rst['mediche_name'];
            $commodity_price = $rst['mediche_price'];
        }

        if (!$addRst) {
            $trans->rollback();
            return ['status' => false, 'msg' => '添加失败'];
        }

        $time = time() - C('ORDER_TIME');
        $time = array('GT', $time);

        $map = [
            'user_id' => $orderRst['user_id'],
            'commodity_price' => $commodity_price,
            'status' => 1,
            'site' => $user['site'],
            'creation_time' => $time]
        ;

        $model = M('order');
        $saveRst = $model->where($map)->save(['status' => 2, 'completion_time' => time()]);

        if ($saveRst === false) {
            $trans->rollback();
            return ['status' => false, 'msg' => '没有找到该订单'];
        }

        //添加日志
        D('Log')->addLog('购买道具'. $commodity_name .', 价格为：'. $commodity_price, $orderRst['user_id']);

        $trans->commit();
        return ['status' => true];
    }
	
	/**
     * 取消订单
     * @author zh
     * @date 2018-1-16
     * @return void
     */
    public function unBuy($order_id)
    {
        
		$order = D('order')->where(['id' =>  $order_id])->find();
		if ($order) {
			$id=$order['id'];
			$data    = [
            'status'     => 3,
        	];
			$rst=D('order')->where(['id' =>  $id])->save($data);
			return ['status' => true];
		} else {
			return ['status' => false, 'msg' => '没有找到该订单'];
		}
		
		

    }
}
