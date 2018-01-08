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
