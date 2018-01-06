<?php

namespace app\models;
use YIi;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $content;
    public $type;
    public $authKey;
    public $accessToken;

    private static $users = [
        0 => ['id'=>0,'username'=>'博物馆'],
        1 => ['id'=>1,'username'=>'专家'],
        2 => ['id'=>2,'username'=>'管理员']

    ];
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $data = \Yii::$app->db->createCommand('SELECT * FROM `sysuser`')->queryAll();
        foreach ($data as $v){
            if (strcasecmp($v['uname'], $username) === 0) {

                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'username',
                    'value' => $v['uname']
                ]));
                User::$users[$v['uid']] = array(
                    'id' => $v['utype'] == '管理员' ? 2 : ($v['utype'] == '专家' ? 1 : 0),
                    'username' => $v['uname'],
                    'password' => $v['upassword'],
                    'content' => $v['content'],
                    'type' => $v['utype'],
                );
                return new User(self::$users[$v['uid']]);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
