<?php

namespace app\models\dao;

use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * ContactForm is the model behind the contact form.
 */
class Museumdata extends Model
{
    public $begin;
    public $end;
    public $file;
    public $subject;
    public $body;
    public $verifyCode;

    public $arr = ['begin','end','file'];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
//            [['file'], 'file', 'skipOnEmpty' => false],
        ];
    }


    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        $param = [];
        foreach ($this->arr as $v){
            if (isset($_POST[$v])){
                $param[$v] = $_POST[$v];
            }
        }
        return 1;
//        $db = new \yii\db\Query();
//        $data = $db->select('mtype')->from('museuminfo')->all();
//        $tmp = [];
//        if (!empty($data)){
//            foreach ($data as $v){
//                $tmp[] = $v;
//            }
//        }
//        return $tmp;
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
