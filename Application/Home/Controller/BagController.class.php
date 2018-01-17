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

    /**
     * 人物详情
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */

    public function personDetails()
    {
        $id = I('get.id');

        if (!$id) {
           $this->error();
        }

        $person = D('person')->getBagPersonDetails($id);

        if (!$person['id']) {
            $this->error();
        }

        if ($person['equipment_id'] != 0) {
           $person['equipment_info'] =D('equipment')->getBagEquipment($person['equipment_id']);
        }

        if ($person['equipment_id_card'] != 0) {
            $person['equipment_card_info'] =D('equipment')->getBagEquipment($person['equipment_id_card']);
        }

        $equipment_all = D('equipment')->getBagEquipment();
        $mediche_all = D('mediche')->getBagMediche();
        $log = D('log')->findLog(session('user_id'), $id, 2);

        $this->assign('person',$person);
        $this->assign('equipment_all',$equipment_all);
        $this->assign('mediche_all',$mediche_all);
        $this->assign('log',$log);
        $this->display('main');
    }

	/*个人中心*/
	public function userMe()
    {
        $user = D('user')->getUser();
        $person = D('person')->getBagPerson();
		$personNum=count($person);
        $sumEarnings = 0;
		foreach ($person as $key => $value) {
			$sumEarnings+=$value['yesterday_capacity'];
		}
		
		$this->assign('user',$user);
        $this->assign('person',$person);
        $this->assign('personNum',$personNum);
        $this->assign('sumEarnings',$sumEarnings);
		
        $this->display('me');
    }
	/*支付注册费*/
	public function zhifu()
    {
        $this->display('zhifu');
    }
	/*取消支付*/
	public function unZhifu()
    {
    	
		$this->redirect(U('bag/userMe','',''));
           
    }


	/*系统公告*/
	public function newspage()
    {
        $this->display('newspage');
    }
	/*帮助中心*/
	public function help()
    {
        $this->display('help');
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

        $this->assign('equipment',$equipment);
        $this->display('equipment');
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

        $this->assign('mediche',$mediche);
        $this->display('mediche');
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