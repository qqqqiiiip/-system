<?php

namespace app\models\dao;

use app\controllers\SiteController;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * ContactForm is the model behind the contact form.
 */
class Museumdatadx extends Model
{
    public $begin;
    public $end;
    public $file;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'required'],
            [['file'], 'file', 'extensions' => 'xls'],
        ];
    }


    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        $model = new self();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstances($model, 'file');
            if (!empty($model->file[0]->tempName)) {
                $result = SiteController::upload(SiteController::$key_dx, $model->file, 'museumdatadx');
                echo $result;
                exit;
            }
        }
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
