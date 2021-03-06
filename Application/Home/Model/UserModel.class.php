<?php

namespace Home\Model;
use Think\Model;
class UserModel extends Model
{

    //获取会员
    public function getUser()
    {
        $user = M('user')
            ->where(['id' => session('user_id')])
            ->find();

        if ($user) {
            return ['status' => true, 'data' => $user];
        } else {
            return ['status' => false, 'msg' => '数据库中没有该会员'];
        }

    }
	
	//获取会员求购订单
    public function getBuyOrder()
    {
    	$userid=session('user_id');
        $buyOrderList = M('user_buy_order')
            ->where( "user_id = $userid || receiving_user_id=$userid")
			->order('creation_time desc')
			->limit(10)
            ->select();

		/*pr(M()->getLastSql());die;*/
		foreach($buyOrderList as &$value) {
            if ($value['receiving_user_id'] == session('user_id')) {
                if ($value['use_time'] + C('ORDER_TIME' ) < time() && $value['status'] != 2) {
                    $value['status'] = 4;
                }
            }

		}

        if ($buyOrderList) {
            return ['status' => true, 'data' => $buyOrderList];
        } else {
            return ['status' => false, 'msg' => '该会员没有求购订单'];
        }

    }
	//获取会员出售订单
    public function getSellOrder()
    {
    	$userid=session('user_id');
        $sellOrderList = M('user_sell_order')
            ->where("user_id = $userid || receiving_user_id=$userid")
			->order('creation_time desc')
			->limit(10)
            ->select();

		foreach($sellOrderList as &$value) {

			if ($value['use_time'] + C('ORDER_TIME' )< time() && $value['status'] != 2 && $value['user_id'] != session('user_id')) {
				$value['status'] = 4;
			}	
		}
        
        if ($sellOrderList) {
            return ['status' => true, 'data' => $sellOrderList];
        } else {
            return ['status' => false, 'msg' => '该会员没有出售订单'];
        }

    }
	//获取会员商城订单
    public function getscOrder()
    {
    	$userid=session('user_id');
        $scOrderList = M('order')
            ->where( "user_id = $userid")
			->order('creation_time desc')
			->limit(10)
            ->select();

		/*pr(M()->getLastSql());die;*/
		foreach($scOrderList as &$value) {
            
                if ($value['creation_time'] + C('ORDER_TIME' ) < time() && $value['status'] != 2) {
                    $value['status'] = 3;
                }

		}

        if ($scOrderList) {
            return ['status' => true, 'data' => $scOrderList];
        } else {
            return ['status' => false, 'msg' => '该会员没有求购订单'];
        }

    }

}
