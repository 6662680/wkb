<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 2018.1.7 
 */
class PresentController extends PrivilegeController
{
	/**
	 * 推广基础参数
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function index()
    {
    	$person = M('person')->select();
		$equipment = M('equipment')->select();
		$mediche = M('mediche')->select();
		
		foreach ($person as &$value) {
			$value['id']='p'.$value['id'];
		}
		foreach ($equipment as &$value) {
			$value['id']='e'.$value['id'];
		}
		foreach ($mediche as &$value) {
			$value['id']='m'.$value['id'];
		}
		
    	$this->assign('person',$person);
		$this->assign('equipment',$equipment);
		$this->assign('mediche',$mediche);
        $this->display();
    }
    
  

    /**
     * 处理赠送参数请求
     * @author zh 
     * @return [type] [description]
     */
    public function present()
    {
    	if (!I('post.userid')) {
    		$this->error('请输入接收会员ID!');
    	} 
    		$userid = I('post.userid');
		$user=M('user')->where(" id=$userid ")->find();
		/*pr(M()->getLastSql());die;*/
		if ($user) {
			$type=substr(I('post.id') , 0 , 1);
			$comid=substr(I('post.id') ,  1);
			if ($type=='p') {
				$person=M('person')->where(" id=$comid ")->find();
				$data    = [
		            'user_id'     => $userid,
		            'person_id'     => $comid,
		            'equipment_id'     => 0,
		            'equipment_id_card'     => 0,
		            'blood'     => $person['person_blood'],
		            'level'     => 1,
		            'yesterday_capacity'     => 0,
		            'start_mining'     => 0,
		            'order_use'     => 0,
		            
		        	];
				$rst=M('Person_bag')->add($data);	
				if ($rst) {
					$this->success('赠送成功!',U('Present/index'),2);
				} else {
					$this->error('赠送失败!');
				}
				
			} elseif($type=='e') {
				$equipment=M('equipment')->where(" id=$comid ")->find();
				$data    = [
		            'user_id'     => $userid,
		            'equipment_id'     => $comid,
		            'equipment_endurance'     => $equipment['equipment_endurance'],
		            'person_id'     => 0,
		            'use'     => 0,
		            'order_use'     => 0,
		            
		        	];
				$rst=M('equipment_bag')->add($data);	
				if ($rst) {
					$this->success('赠送成功!',U('Present/index'),2);
				} else {
					$this->error('赠送失败!');
				}
			}else{
				$mediche=M('mediche')->where(" id=$comid ")->find();
				$data    = [
		            'user_id'     => $userid,
		            'mediche_id'     => $comid,
		            'mediche_type'     => 0,
		            'mediche_num'     => 1,
		            
		        	];
				$rst=M('mediche_bag')->add($data);	
				if ($rst) {
					$this->success('赠送成功!',U('Present/index'),2);
				} else {
					$this->error('赠送失败!');
				}
			}
			
			/*pr($comid);die;*/
		} else {
			$this->error('没有该会员!');
		}
    	
    	
		
    }
    
}