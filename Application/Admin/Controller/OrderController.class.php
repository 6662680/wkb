<?php
namespace Admin\Controller;
use Think\Controller;
/**
 *
 * @category   OrderController
 * @package    User
 * @author  stone <664577655@qq.com>
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
     * @author liyang
     * @since  2017年11月
     */
    public function lists(){
        if (I('get.start_time')){

            $time1 = strtotime(I('get.start_time'));
            if (I('get.end_time') && I('get.end_time') >= I('get.start_time')){
                $time2 = strtotime(I('get.end_time'))-1 + 86400;

            } else {
                $time2 = strtotime(I('get.start_time'))-1 + 86400;
            }
            $where['creation_time'] = array( array('egt',$time1), array('elt',$time2), 'and');

        }
        if (I('get.user')){
            $where['id|email|order_number'] = str_replace(' ','',I('get.user'));
        }

        if (!$where){$where = "";}
        list($userData,$show) = D('Order')->getOrderData($where);

        foreach ($userData as &$value) {
            if ($value['status'] == 0) {
                $value['status'] = '未付款';
            }

            if ($value['status'] == 1) {
                $value['status'] = '已付款，等待平台响应';
            }

            if ($value['status'] == 2) {
                $result['status'] = '完成订单';
            }

            if ($value['status'] == 3) {
                $result['status'] = '退单';
            }
        }

        $this->assign("user",$userData);
        $this->assign("page",$show);
        $this->display('orderList');
    }
    /**
     *  查看会员详情
     *
     * @return void
     * @author liyang
     * @since  2016年12月7日
     */
    public function userShow(){ 
        $this->assign("user",D('User')->getUserDetail(I('get.uid')));
        $this->display();
    }
}