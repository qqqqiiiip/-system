<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class GetInfo extends Model
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
            // name, email, subject and body are required
//            [['begin','end'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        $data = \Yii::$app->db->createCommand('SELECT * FROM `sysuser`')->queryAll();
        $tmp = [];
        foreach ($data as $v){
            $tmp[] = $v;
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
