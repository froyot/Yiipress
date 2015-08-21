<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use themeBase\models\SiteModel;

/**
* Login & Register & ResetPassword
*/
class SiteController extends BasicController
{
  public $activeMenu = 'cosepan';

  /**
   * @inheritdoc
  */
  public function actions()
  {
    return ['error' => ['class' =>'yii\web\ErrorAction' ], ];
  }

  public function actionIndex()
  {
    $content = SiteModel::getSiteIndexContent($this);
    return $this->render('index',['content'=>$content,'siteTitle'=>'test']);
  }

  public function actionLogin()
  {
    if (!Yii::$app->user->isGuest)
    {
      return $this->goHome();
    }

    $model = new LoginForm();
    if ( $model ->load(Yii::$app->request->post()) && $model->login() )
    {
      return $this ->goBack();
    }
    else
    {
      return $this ->render('login', ['model' =>$model,

      ]);
    }
  }

  public function actionSignup()
  {
    $model = new SignupForm();
    if ( $model ->load( Yii::$app->request->post() ) && $model ->register() )
    {
      $this ->redirect(['site/index']);
    }
    else
      return $this ->render('signup', ['model' =>$model, ]);
  }
}
