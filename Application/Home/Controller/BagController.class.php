<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class BagController extends BaseController

{
    public function __construct()
	{
        parent::__construct();

	}

    /**
     * 获取人物
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function person()
    {
        $person = D('person')->getBagPerson();
        $this->assign('person',$person);
        $this->display('index');
    }

    public function personDetails()
    {
        $id = I('get.id');

        $person = D('person')->getBagPersonDetails($id);
        pr($person);
        $this->assign('person',$person);
        $this->display('main');
    }

    /**
     * 获取装备
     * @author LiYang
     * @date 2018-1-8
     * @return void
     */
    public function equipment()
    {
        $equipment = D('equipment')->getBagEquipment();
        returnajax(true, $equipment);
    }

    /**
     * 获取药品
     * @author LiYang
     * @date 2018-1-8
     * @return void
     */
    public function mediche()
    {
        $mediche = D('mediche')->getBagMediche();
        returnajax(true, $mediche);
    }

    /**
     * 卸下或者装备道具
     * @author LiYang
     * @date 2018-1-11
     * @return void
     */
    public function switchover()
    {
        $post = I('post.');
        $equipment = D('equipment')->switchover($post['person_bage_id'], $post['equipment_bag_id']);

        if ($equipment['status'] == true) {
            returnajax(true);
        } else {
            returnajax(false, '' , $equipment['msg']);
        }
    }

    /**
     * 使用药水
     * @author LiYang
     * @date 2018-1-12
     * @return void
     */
    public function useMediche()
    {
        $post = I('post.');
        $mediche = D('mediche')->useMediche($post['person_bag_id'], $post['mediche_bag_id']);

        if ($mediche['status'] == true) {
            returnajax(true);
        } else {
            returnajax(false, '' , $mediche['msg']);
        }
    }
}