<?php

namespace Home\Model;
use Think\Model;
class EquipmentModel extends Model
{

    //获取装备
    public function getBagEquipment()
    {
        $person = M('equipment_bag')
            ->where(['user_id' => session('user_id')])
            ->join('left join equipment on equipment_bag.equipment_id = equipment.id')
            ->field('equipment_bag.*, equipment.equipment_name,equipment.equipment_img,equipment.equipment_endurance,equipment.equipment_protect,equipment_multiple')
            ->select();

        foreach ($person as &$value) {
            $value['capacity'] = $value['person_capacity'] + ($value['person_property'] * $value['level']);
        }

        return $person;
    }

    //获取商城装备
    public function getStoreEquipment()
    {
        $person = M('equipment')
            ->where(['status' => 0])
            ->select();

        return $person;
    }

}
