<?php
namespace Admin\Model;
use Think\Model;
/**
 *
 * @category   UserController
 * @package    User
 * @author  stone <shijiangbo929@163.com>
 * @license
 * @version    PHP version 5.4
 * @link
 * @since   2016年12月7日
 *
 **/
class UserSellOrderModel extends Model{
    /*
     * 查询会员
     * @param $where 查询条件
     * @return 成功返回$data，失败返回false;
     * @author stone
     * @since 2016-12-7
     * */
    public function getusersellorderData($where){
        if ($where){
            $count = $this->field('id')->where($where)->count();
        }else{
            $count = $this->field('id')->count();
        }       
	$Page = new\Think\Page($count,10);
	$Page->setConfig('header','共%TOTAL_ROW%条');
	$Page->setConfig('prev','上一页');
	$Page->setConfig('next','下一页');
	$Page->setConfig('first','首页');
	$Page->setConfig('last','末页');
	$Page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
	$show = $Page->show();
        if ($where){
            $data = $this
                ->order('creation_time desc')
                ->where($where)
                ->limit($Page->firstRow.','.$Page->listRows)
                ->select();
        } else {
            $data = $this
                ->order('creation_time desc')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->select();
        }        
        if (!$data){return false; } 
        return array($data,$show,$count);
    }
    /*
     * 查询单个会员
     * @param $id 查询条件
     * @return 成功返回$data，失败返回false;
     * @author stone
     * @since 2016-12-8
     * */
    public function getUserDetail($id){
        if (!isset($id)){return false;}
        $data = $this->find($id);
        return $data;
    }
    
}