<?php

namespace app\models;

use yii\db\ActiveRecord;

class Museuminfo extends ActiveRecord
{
    public static function add($param){
        $obj = new self;
        foreach ($param as $k => $v){
            $obj->$k = $v;
        }
        return $obj->save();
    }
}