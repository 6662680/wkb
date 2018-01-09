<?php

namespace Home\Model;
use Think\Model;
class OrderModel extends Model
{


    /**
     * 购买商品
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

        $rst = M(C('COMMODITY_TYPE')[$commodity_type])->where(['id' => $commodity_id, 'status' => 0])->find();

        if (empty($rst)) {
            returnajax('fasle', '', '不存在的商品');
        }

        $this->creationOrder($user_id, $commodity_id, $commodity_type, $rst[C('COMMODITY_TYPE')[$commodity_type].'_price']);
    }


    //创建订单
    public function creationOrder($user_id, $commodity_id, $commodity_type, $commodity_price)
    {
        if (empty($user_id) || empty($commodity_id) || empty($commodity_type) || empty($commodity_price)) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        $rst = M('user')->where(['id' => $user_id])->field('site')->find();

        if ($rst['site']) {
            return ['status' => false, 'msg' => '您没有设置您的玩客币地址，请先设置地址'];
        }

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
            return ['status' => true];
        } else {
            return ['status' => false, 'msg' => '创建订单失败，请稍候再购买'];
        }
    }

    // 完成购买
    public function accomplishBuy($user_id, $commodity_id,$commodity_type, $commodity_price, $site)
    {
        if (empty($user_id) || empty($commodity_id) || empty($commodity_type)) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        $rst = M(C('COMMODITY_TYPE')[$commodity_type])->where(['id' => $commodity_id])->find();

        if (!$rst) {
            return ['status' => false, 'msg' => '不存在的商品'];
        }

        $trans = new Model();
        $trans->startTrans();

        if ($commodity_type == 1) {
            $model = M('person_bag');
            $model->user_id = $user_id;
            $model->person_id = $commodity_id;
            $model->equipment_id = 0;
            $model->equipment_id_card = 0;
            $model->blood = $rst['person_blood'];
            $model->level = 1;
            $model->yesterday_capacity = 0;
            $model->start_mining = 0;
            $rst = $model->add();

            $commodity_name = $rst['person_name'];
            $commodity_price = $rst['person_price'];
        }

        if ($commodity_type == 2) {
            $model = M('equipment_bag');
            $model->user_id = $user_id;
            $model->equipment_id = $commodity_id;
            $model->equipment_endurance = $rst['equipment_endurance'];
            $model->person_id = 0;
            $model->use = 0;
            $rst = $model->add();

            $commodity_name = $rst['equipment_name'];
            $commodity_price = $rst['equipment_price'];
        }

        if ($commodity_type == 3) {
            $model = M('mediche_bag');
            $model->user_id = $user_id;
            $model->equipment_id = $commodity_id;
            $model->equipment_endurance = $rst['equipment_endurance'];
            $model->person_id = 0;
            $model->use = 0;
            $rst = $model->add();

            $commodity_name = $rst['mediche_name'];
            $commodity_price = $rst['mediche_price'];
        }

        if (!$rst) {
            $trans->rollback();
            return ['status' => false, 'msg' => '添加失败'];

        }

        $time = time() - C('ORDER_TIME');
        $time = array('GT', $time);

        $map = [
            'user_id' => $user_id,
            'commodity_price' => $commodity_price,
            'status' => 1,
            'site' => $site,
            'creation_time' => $time]
        ;

        $model = M('order');
        $saveRst = $model->where($map)->save(['status' => 2]);

        if ($saveRst === false) {
            $trans->rollback();
            return ['status' => false, 'msg' => '没有找到该订单'];
        }
        
        //添加日志
        D('Log')->addLog('购买道具'. $commodity_name .', 价格为：'. $commodity_price, $rst);

        $trans->commit();
        return ['status' => true];
    }
}
