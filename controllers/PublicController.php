<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Users;

/**
 * Login & Register & ResetPassword
 */
class PublicController extends Controller
{
  public $layout = false;

  public function actionLogin()
  {
    if (Yii::$app->request->isPost)
    {
      $res = Users::login();
      if ($res)
      {
        $this->goHome();
      }
      else
      {
        Yii::$app->session->setFlash('tips', "用户名或密码错误");
      }
    }

    return $this->render('login');


  }

  public function actionRegister()
  {
    exit('closed register');
    if (Yii::$app->request->isPost)
    {
      $user = new Users();
      $res  = $user->register(
                              Yii::$app->request->post('user_login'),
                              Yii::$app->request->post('password')
                            );
      if ($res)
      {
        $this->goHome();
      }
      else
      {

        Yii::$app->session->setFlash(
                                    'tips',
                                    $user->getFirstError('error_label')
                                    );
      }
    }
    return $this->render('register');
  }

  public function actionResetPassword()
  {
    exit('closed');
    return $this->render('resetPassword');
  }

  public function actionLogout()
  {
        $user = Yii::$app->user->getIdentity();
        Yii::$app->user->logout();

        return $this->goHome();
  }
}
