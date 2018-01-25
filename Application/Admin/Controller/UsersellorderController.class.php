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
class UsersellorderController extends PrivilegeController{
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
				$this->error('没有该会员!',U('Usersellorder/lists'),2);
				exit;
			}
			
        }

        if (!$where){$where = "";}
        list($usersellorderData,$show) = D('user_sell_order')->getusersellorderData($where);
		/*pr($usersellorderData);die;*/

        foreach ($usersellorderData as $key => $value) {
			if ($value['creation_time'] + C('ORDER_TIME' )< time() &&$value['status']==1) {
				$usersellorderData[$key]['status'] = 4;
			}
			$commodity_type=$value['commodity_type'];
			if ($commodity_type==1) {
    			$usersellorderData[$key]['commodity_type']='人物';
			} elseif($commodity_type==2) {
				$usersellorderData[$key]['commodity_type']='道具';
			} elseif($commodity_type==3) {
				$usersellorderData[$key]['commodity_type']='食物';
			}
			
        }

        $this->assign("usersellorderData",$usersellorderData);
        $this->assign("page",$show);
        $this->display('usersellorderList');
    }
    
}