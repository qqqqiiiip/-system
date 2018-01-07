<?php

namespace app\models\dao;
use yii\web\UploadedFile;
use Yii;
use yii\base\Model;
use app\controllers\SiteController;
/**
 * ContactForm is the model behind the contact form.
 */
class Expertpoint extends Model
{
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
                $result = SiteController::upload(SiteController::$key_expertpoint, $model->file, 'expertpoint');
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
