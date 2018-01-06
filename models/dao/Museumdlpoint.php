<?php

namespace app\models\dao;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Museumdlpoint extends Model
{
    public $begin;
    public $end;
    public $subject;
    public $body;
    public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
//            [['begin','end'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {

        $db = new \yii\db\Query();
        $data = $db->select('uname,upassword')->from('sysuser')->all();
//        $data = $db->createCommand('SELECT * FROM `sysuser`')->queryAll();
        $tmp = [];
        if (!empty($data)){
            foreach ($data as $v){
                $tmp[] = $v;
            }
        }
        return $tmp;
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {


    }
}
