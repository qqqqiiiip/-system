<?php

namespace app\models;

use app\models\dao\Museumdata;
use yii\db\ActiveRecord;

class Sysuser extends ActiveRecord
{
    public static function tableName()
    {
        return 'sysuser';
    }

    public function rules()
    {
        return [
            [['uname'], 'required'],
        ];
    }

    public static function add($param){
        $obj = new self;
        foreach ($param as $k => $v){
            $obj->$k = $v;
        }
        return $obj->save();
    }
}