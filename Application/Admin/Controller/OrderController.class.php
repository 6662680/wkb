<?php
namespace Admin\Controller;
use Think\Controller;
/**
 *
 * @category   OrderController
 * @package    User
 * @author  stone <xx@qq.com>
 * @license
 * @version    PHP version 5.6
 * @link
 *
 *
 **/
class OrderController extends PrivilegeController{
    /**
     *  订单
     *
     * @return void
     * @author zh
     * @since  2018.1.11
     */
    public function lists(){
        if (I('post.start_time')){

            $time1 = strtotime(I('post.start_time'));
            if (I('post.end_time') && I('post.end_time') >= I('post.start_time')){
                $time2 = strtotime(I('post.end_time'))-1 + 86400;

            } else {
                $time2 = strtotime(I('post.start_time'))-1 + 86400;
            }
            $where['creation_time'] = array( array('egt',$time1), array('elt',$time2), 'and');

        }
        if (I('post.user')){
        	
        	$sss=str_replace(' ','',I('post.user'));
			$oneuserList = M('user')->where("id = '$sss' ")->find();
			if ($oneuserList) {
				$search=$oneuserList['id'];
            	$where['user_id'] = $search;
			} else{
				$this->error('没有该会员!',U('Order/lists'),2);
				exit;
			}
			
        }

        if (!$where){$where = "";}
        list($orderData,$show) = D('Order')->getOrderData($where);
		/*pr($orderData);die;*/

        foreach ($orderData as $key => $value) {
            /*$user_id=$value['user_id'];
    		$nuserList = M('user')->where("id = '$user_id' ")->find();
			$orderData[$key]['user_id']=$nuserList['mobile'];*/
			
			$commodity_type=$value['commodity_type'];
			$commodity_id=$value['commodity_id'];
			if ($commodity_type==1) {
    			$npersonList = M('person')->where("id = '$commodity_id' ")->find();
    			$orderData[$key]['commodity_type']='人物';
				$orderData[$key]['commodity_id']=$npersonList['person_name'];
			} elseif($commodity_type==2) {
				$nequipmentList = M('equipment')->where("id = '$commodity_id' ")->find();
				$orderData[$key]['commodity_type']='道具';
				$orderData[$key]['commodity_id']=$nequipmentList['equipment_name'];
			} elseif($commodity_type==3) {
				$nmedicheList = M('mediche')->where("id = '$commodity_id' ")->find();
				$orderData[$key]['commodity_type']='食物';
				$orderData[$key]['commodity_id']=$nmedicheList['mediche_name'];
			}
			
        }

        $this->assign("orderData",$orderData);
        $this->assign("page",$show);
        $this->display('orderList');
    }
    /**
     *  查看会员详情
     *
     * @return void
     * @author zh
     * @since  2018.1.11
     */
    public function userShow(){ 
        $this->assign("user",D('User')->getUserDetail(I('get.uid')));
        $this->display();
    }
}