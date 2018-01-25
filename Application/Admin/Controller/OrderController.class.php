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
			if ($value['creation_time'] + C('ORDER_TIME' )< time() &&$value['status']==1) {
				$orderData[$key]['status'] = 4;
			}
			
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

		//当天总收入
		$map['creation_time'] = array(
			    array('egt',strtotime(date('Y-m-d',time()))),
			    array('lt',strtotime(date('Y-m-d',time()))+86400)
			);
		/*$where['status']=2;
		$currentOrder=M('order')->where($map)->where($where)->field('SUM(commodity_price)')->find();*/
		/*pr($currentOrder['sum(commodity_price)']);die;*/
		/*$currentSellOrder=M('user_sell_order')->where($map)->where($where)->field('SUM(commodity_price)')->find();
		$sumCurrentSellOrder=$currentSellOrder['sum(commodity_price)']*0.05;
		$sumCurrentOrder=$currentOrder['sum(commodity_price)'];
		$sumMoney=$sumCurrentSellOrder+$sumCurrentOrder;*/
		/*pr($sumMoney);die;*/
		$sumM=M('earnings')->where($map)->field('SUM(price)')->find();
		/*pr($sumM['sum(price)']);die;*/
		
		/*pr(C('RETURN_MONEY'));die;*/
		$biliList=M('config')->where(' id = 2')->find();
		
		$bili=$biliList['value'];
		$bili=(float)$bili;
		/*pr($bili);die;*/
		$rSumM=$sumM['sum(price)']*$bili;
		
		$this->assign("bili",$bili);
		$this->assign("sumMoney",$sumM['sum(price)']);
		$this->assign("rSumMoney",$rSumM);
		
        $this->assign("orderData",$orderData);
        $this->assign("page",$show);
        $this->display('orderList');
    }

	/**
     * 处理比例修改请求
     * @author zh 
     * @return [type] [description]
     */
    public function returnMoney()
    {
    	$bili=I('post.bili');
		/*pr($bili);die;*/
    	if (!$bili) {
    		$this->error('请填写比例参数!');
    	} 
    	$data    = [
		    'value'     => $bili,
		            
		];
		$rst=M('config')->where('id=2 ')->save($data);
		
		$this->success('比例参数修改成功!',U('Order/lists'),1);
    }

    
}