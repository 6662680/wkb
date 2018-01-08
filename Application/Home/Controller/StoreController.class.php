<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class StoreController extends BaseController

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
        $person = D('person')->getStorePerson();
        returnajax(true, $person);
    }

    /**
     * 获取装备
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function equipment()
    {
        $equipment = D('equipment')->getStoreEquipment();
        returnajax(true, $equipment);
    }

    /**
     * 获取药品
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function mediche()
    {
        $mediche = D('mediche')->getStoreMediche();
        returnajax(true, $mediche);
    }

}