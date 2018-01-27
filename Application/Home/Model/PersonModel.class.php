<?php

namespace Home\Model;
use Think\Model;
class PersonModel extends Model
{

    //获取背包人物
    public function getBagPerson()
    {
        $person = M('person_bag')
            ->where(['user_id' => session('user_id')])
            ->join('left join person on person_bag.person_id = person.id')
            ->field('person_bag.*, person.person_name,person.person_img,person.person_property,person.person_capacity')
            ->select();

        foreach ($person as &$value) {
            $value['capacity'] = $value['person_capacity'] + ($value['person_property'] * $value['level']);
            $value['basecapacity'] = $value['capacity'];
            if ($value['equipment_id'] != 0) {
                $value['capacity'] = $value['capacity'] * $this->getPersonCapacity($value['id']);
            }

        }

        return $person;
    }

    //获取人物总属性
    public function getPersonCapacity($person_id)
    {
        $rst = M('person_bag')->where(['id' => $person_id])->find();

        if ($rst['equipment_id'] != 0) {
            $equipment = M('equipment_bag')->where(['id' => $rst['equipment_id']])->find();
            $equipment_bag = M('equipment')->where(['id' => $equipment['equipment_id']])->find();
        }

        return $equipment_bag['equipment_multiple'];
    }

    //获取背包人物
    public function getBagPersonDetails($id)
    {
        $person = M('person_bag')
            ->where(['user_id' => session('user_id'), 'person_bag.id' => $id])
            ->join('left join person on person_bag.person_id = person.id')
            ->field('person_bag.*, person.person_name,person.person_img,person.person_property,person.person_capacity')
            ->find();

        $person['capacity'] = $person['person_capacity'] + ($person['person_property'] * $person['level']);
        $person['basecapacity'] = $person['capacity'];
        if ($person['equipment_id'] != 0) {
            $person['capacity'] = $person['capacity'] * $this->getPersonCapacity($person['id']);
        }

        return $person;
    }

    //获取商城人物
    public function getStorePerson()
    {
        $person = M('person')
            ->where(['status' => 0])
            ->select();

        return $person;
    }

}
