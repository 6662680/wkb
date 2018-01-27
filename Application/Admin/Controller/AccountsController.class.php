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
class AccountsController extends PrivilegeController{
    /**
     *  注册收入
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
        

        if (!$where){$where = "";}
        list($earningsData,$show) = D('Earnings')->getEarningsData($where);
		/*pr($orderData);die;*/

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
		$sumzcM=M('earnings')->where($map)->where(' type= 1')->field('SUM(price)')->find();
		/*pr($sumM['sum(price)']);die;*/
		
		/*pr(C('RETURN_MONEY'));die;*/
		$biliList=M('config')->where(' id = 2')->find();
		
		$bili=$biliList['value'];
		$bili=(float)$bili;
		/*pr($bili);die;*/
		$rSumM=$sumM['sum(price)']*$bili;
		
		$this->assign("bili",$bili);
		$this->assign("sumMoney",$sumM['sum(price)']);
		$this->assign("sumzcM",$sumzcM['sum(price)']);
		$this->assign("rSumMoney",$rSumM);
		
        $this->assign("earningsData",$earningsData);
        $this->assign("page",$show);
        $this->display('accounts');
    }
	/**
     *  商城收入
     *
     * @return void
     * @author zh
     * @since  2018.1.11
     */
    public function sclists(){
    	/*pr('545454');die;*/
        if (I('post.start_time')){

            $time1 = strtotime(I('post.start_time'));
            if (I('post.end_time') && I('post.end_time') >= I('post.start_time')){
                $time2 = strtotime(I('post.end_time'))-1 + 86400;

            } else {
                $time2 = strtotime(I('post.start_time'))-1 + 86400;
            }
            $where['creation_time'] = array( array('egt',$time1), array('elt',$time2), 'and');

        }
        

        if (!$where){$where = "";}
        list($earningsData,$show) = D('Earnings')->getscEarningsData($where);
		/*pr($orderData);die;*/

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
		$sumscM=M('earnings')->where($map)->where(' type= 2')->field('SUM(price)')->find();
		/*pr($sumM['sum(price)']);die;*/
		
		/*pr(C('RETURN_MONEY'));die;*/
		$biliList=M('config')->where(' id = 2')->find();
		
		$bili=$biliList['value'];
		$bili=(float)$bili;
		/*pr($bili);die;*/
		$rSumM=$sumM['sum(price)']*$bili;
		
		$this->assign("bili",$bili);
		$this->assign("sumMoney",$sumM['sum(price)']);
		$this->assign("sumscM",$sumscM['sum(price)']);
		$this->assign("rSumMoney",$rSumM);
		
        $this->assign("earningsData",$earningsData);
        $this->assign("page",$show);
        $this->display('scaccounts');
    }
	/**
     *  手续费收入
     *
     * @return void
     * @author zh
     * @since  2018.1.11
     */
    public function sxflists(){
        if (I('post.start_time')){

            $time1 = strtotime(I('post.start_time'));
            if (I('post.end_time') && I('post.end_time') >= I('post.start_time')){
                $time2 = strtotime(I('post.end_time'))-1 + 86400;

            } else {
                $time2 = strtotime(I('post.start_time'))-1 + 86400;
            }
            $where['creation_time'] = array( array('egt',$time1), array('elt',$time2), 'and');

        }
        

        if (!$where){$where = "";}
        list($earningsData,$show) = D('Earnings')->getsxfEarningsData($where);
		/*pr($orderData);die;*/

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
		$sumsxfM=M('earnings')->where($map)->where(' type= 3')->field('SUM(price)')->find();
		/*pr($sumM['sum(price)']);die;*/
		
		/*pr(C('RETURN_MONEY'));die;*/
		$biliList=M('config')->where(' id = 2')->find();
		
		$bili=$biliList['value'];
		$bili=(float)$bili;
		/*pr($bili);die;*/
		$rSumM=$sumM['sum(price)']*$bili;
		
		$this->assign("bili",$bili);
		$this->assign("sumMoney",$sumM['sum(price)']);
		$this->assign("sumsxfM",$sumsxfM['sum(price)']);
		$this->assign("rSumMoney",$rSumM);
		
        $this->assign("earningsData",$earningsData);
        $this->assign("page",$show);
        $this->display('sxfaccounts');
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
		
		$this->success('比例参数修改成功!',U('Accounts/lists'),1);
    }

    
}