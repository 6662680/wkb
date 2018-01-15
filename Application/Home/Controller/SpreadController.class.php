<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class SpreadController extends BaseController

{
    public function __construct()
	{
        parent::__construct();

	}

    /**
     * 返还上线会员推广奖励
     * @author zh
     * @date 2018-1-13
     * @return void
     */
    public function award()
    {
    	$user_id=5;
		/*获取某会员的总消费金额*/
        $rst = D('spread')->getSumPrice($user_id);
		/*pr($rst);*/
		/*操作:当会员消费满一定金额且存在上线会员且从未给上线会员返还过奖励时则给上线会员返奖励*/
		$userList = M('user')->where("id = '$user_id' ")->find();
		/*pr($userList);*/
		$spread = M('spread')->find();
		/*pr($spread['award']);*/
		if ($userList['up_user']&&$rst['sumprice']>$spread['neck']&&$userList['influence']==1) {
			$upid=$userList['up_user'];
			$upuser = M('user')->where("id = '$upid' ")->find();
			$data = [
    		'point' => $spread['award']+$upuser['point'],
    		];
			/*pr($data);*/
			$data2 = [
    		'influence' => 2,
    		];
			M('user')->where("id = $upid")->save($data);
			M('user')->where("id = $user_id")->save($data2);
			//添加日志
        	D('Log')->addLog('会员'. $user_id .'总消费满'. $spread['neck'].'系统返还上线会员'.$userList['up_user'].'推广奖励'.$spread['award'].'积分', $user_id);
			
		} 
    }
	/**
     * 首次消费，官方奖励道具
     * @author zh
     * @date 2018-1-14
     * @return void
     */
    public function awardEquipment()
    {
    	$user_id=2;
		/*获取某会员的消费次数，从而判断是否第一次消费*/
    	$rst=M('order')->where(['user_id' => $user_id, 'status' => 2])->field('COUNT(*) as num ')->find();
		$rst2=M('first_buy')->where(['id' => 1])->find();
		$rst3=M('equipment')->where(['id' => $rst2['aequipment_id']])->find();
        /*pr($rst2['aequipment_id']);die;*/
		if ($rst['num']==1) {
			$data = [
    		'user_id' => $user_id,
    		'equipment_id' => $rst2['aequipment_id'],
    		
    		];
			M('equipment_bag')->where("id = $user_id")->add($data);
			//添加日志
        	D('Log')->addLog('会员'. $user_id .'首次消费，系统奖励一个'.$rst3['equipment_name'], $user_id);
		}
		
    }

}