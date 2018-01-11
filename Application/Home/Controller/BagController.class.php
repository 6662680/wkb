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
        returnajax(true, $person);
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
     * @date 2018-1-8
     * @return void
     */
    public function switchover()
    {
        $post = I('post.');
        $mediche = D('equipment')->switchover($post['person_id'], $post['equipment_id']);
        returnajax(true, $mediche);
    }
}