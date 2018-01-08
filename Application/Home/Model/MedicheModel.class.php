<?php

namespace Home\Model;
use Think\Model;
class MedicheModel extends Model
{

    //获取背包药水
    public function getBagMediche()
    {
        $person = M('mediche_bag')
            ->where(['user_id' => session('user_id')])
            ->join('left join mediche on mediche_bag.mediche_id = mediche.id')
            ->field('mediche_bag.*, mediche.mediche_name, mediche.mediche_img,mediche.mediche_treat')
            ->select();

        return $person;
    }

    //获取商城药水
    public function getStoreMediche()
    {
        $person = M('mediche')
            ->where(['status' => 0])
            ->select();
        
        return $person;
    }

}
