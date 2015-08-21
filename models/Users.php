<?php

namespace app\models;
use yii\web\IdentityInterface;

use Yii;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property string $ID
 * @property string $user_login
 * @property string $user_pass
 * @property string $user_nicename
 * @property string $user_email
 * @property string $user_url
 * @property string $user_registered
 * @property string $user_activation_key
 * @property integer $user_status
 * @property string $display_name
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $authKey;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'user_status'], 'integer'],
            [['user_registered'], 'safe'],
            [['user_login', 'user_activation_key'], 'string', 'max' => 60],
            [['user_pass'], 'string', 'max' => 64],
            [['user_nicename'], 'string', 'max' => 50],
            [['user_email', 'user_url'], 'string', 'max' => 100],
            [['display_name'], 'string', 'max' => 250],
            [['user_login','user_pass','user_registered'],'required','on'=>'register']
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'user_login' => 'User Login',
            'user_pass' => 'User Pass',
            'user_nicename' => 'User Nicename',
            'user_email' => 'User Email',
            'user_url' => 'User Url',
            'user_registered' => 'User Registered',
            'user_activation_key' => 'User Activation Key',
            'user_status' => 'User Status',
            'display_name' => 'Display Name',
        ];
    }

    public static function findIdentity($id)
    {
      return static::findOne($id);
    }

    public static function findIdentityByAccessToken($user_email, $type = null)
    {
      return static::findOne(['user_email' => $user_email]);
    }

    public function getId()
    {
      return $this->ID;
    }

    public function getAuthKey()
    {
      return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
      return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->user_pass);
    }

    /**
     * 用户登录
     */
    public static function login($rememberMe = false)
    {
        $userLogin = Yii::$app->request->post('user_login');
        $password = Yii::$app->request->post('password');
        $user = Users::findOne(['user_login'=>$userLogin]);
        if( $user && $user->validatePassword($password,$user->user_pass))
        {
            $data = Yii::$app->user->login($user, $rememberMe ? 3600 * 24 * 30 : 0);
            return $data;
        }
        else
        {

            return false;
        }
    }

    /**
     * 用户注册
     * @param  string $userLogin 用户名
     * @param  密码 $password  密码
     * @return Boolean     是否注册成功
     */
    public function register($userLogin,$password)
    {
        if( Users::findOne(['user_login'=>$userLogin]) )
        {
            $this->addError('error_label','用户名已经存在');
            return false;
        }
        $this->user_login = $userLogin;
        $this->display_name = $this->user_login;
        $this->user_nicename = $this->user_login;
        $this->user_pass = Yii::$app->security->generatePasswordHash($password);
        $this->user_registered = date('Y-m-d H:i:s');
        $this->setScenario('register');
        $res = $this->save();
        if( $res )
        {
            //登录该用户
            Yii::$app->user->login($this);
            return true;
        }
        else
        {
            $this->addError('error_label','注册失败');
            return false;
        }

    }
}
