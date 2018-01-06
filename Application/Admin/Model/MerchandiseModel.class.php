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
class MerchandiseModel extends Model{
    /*
     * 商品
     * @param $where 查询条件
     * @return 成功返回$data，失败返回false;
     * @author stone
     * @since 2017-11-15
     * */
    public function getMerchandiseData($where){
        if ($where){
            $count = $this->field('uid')->where($where)->count();
        }else{
            $count = $this->field('uid')->count();
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
                ->order('id desc')
                ->where($where)
                ->limit($Page->firstRow.','.$Page->listRows)
                ->select();
        } else {
            $data = $this
                ->order('id desc')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->select();
        }        
        if (!$data){return false; } 
        return array($data,$show,$count);
    }
    /*
     * 查询单个商品
     * @param $id 查询条件
     * @return 成功返回$data，失败返回false;
     * @author stone
     * @since 2016-12-8
     * */
    public function getMerchandiseDetail($id){

        if (!isset($id)){return false;}
        $data = $this->find($id);
        return $data;
    }
    
}