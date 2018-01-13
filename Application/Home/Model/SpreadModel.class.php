<?php

namespace Home\Model;
use Think\Model;
class SpreadModel extends Model
{

    Protected $autoCheckFields = false;
	
	public function getPriceOrNum($user_id)
    {
    	$rst1=M('order')->where(['user_id' => $user_id])->field('SUM(commodity_price)')->select();
    	$rst2=M('order')->where(['user_id' => $user_id])->field('count(user_id)')->select();
		
		return ['sumprice' => $rst1[0]['sum(commodity_price)'],'num' => $rst2[0]['count(user_id)'],];
    }

    
}
