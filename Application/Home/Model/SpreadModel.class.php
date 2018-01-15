<?php

namespace Home\Model;
use Think\Model;
class SpreadModel extends Model
{

    Protected $autoCheckFields = false;

	/**
	 * 查询出某个会员消费总金额及数量
	 * @author zh
	 * @date 2018-1-13
	 * @return void
	 */
	public function getPriceOrNum($user_id)
    {
    	$rst1 = M('order')->where(['user_id' => $user_id])->field('SUM(commodity_price) as sum')->find();

		return ['sumprice' => $rst1['sum']];
    }

    
}
