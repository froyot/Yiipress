<?php

namespace app\models;
use yii\web\IdentityInterface;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $access_token
 * @property string $last_ip
 * @property string $auth_key
 * @property string $role
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'access_token', 'last_ip', 'auth_key'], 'required'],
            [['role'], 'string'],
            [['username', 'password', 'access_token', 'last_ip', 'auth_key'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'access_token' => 'Access Token',
            'last_ip' => 'Last Ip',
            'auth_key' => 'Auth Key',
            'role' => 'Role',
        ];
    }


    /**
     * 实现接口的函数
     */

    public static function findIdentity($id)
    {
      return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
      return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
      return $this->id;
    }

    public function getAuthKey()
    {
      return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
      return $this->authKey === $authKey;
    }


     
}
