<?php

namespace Home\Model;
use Think\Model;
class SpreadModel extends Model
{

    Protected $autoCheckFields = false;
	
	public function getSumPrice($user_id)
    {
    	$rst=M('order')->where(['user_id' => $user_id])->field('SUM(commodity_price)')->find();
		
		return ['sumprice' => $rst['sum(commodity_price)']];
    }

    
}
