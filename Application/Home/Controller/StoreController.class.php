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
		foreach ($person as $key => $value) {
			$capacity=$value['person_capacity']*24;
		}
		
		/*pr($person);die;*/
		$this->assign('person',$person);
		$this->assign('capacity',$capacity);
        $this->display('sc');
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
		
        $this->assign('equipment',$equipment);
        $this->display('sc_item');
    }

    /**
     * 获取药品
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function mediche()
    {
        $medicheList = D('mediche')->getStoreMediche();
		$mediche=$medicheList['data'];
		/*pr($mediche['data']);die;*/
        $this->assign('mediche',$mediche);
        $this->display('sc_food');
    }

}