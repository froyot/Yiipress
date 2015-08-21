<?php
namespace app\controllers\admin;
use Yii;
use app\models\FormModel\PostsForm;
use app\models\TermTaxonomy;
use app\models\Posts;
use app\models\dataModel\GeneralSetting;
use app\models\dataModel\ReadingSetting;

class SettingController extends BaseController
{
  public function actionGeneral()
  {
    $setting = new GeneralSetting();

    //常规设置
    if( Yii::$app->request->isPost )
    {
      $setting->setScenario('set');
      $res = $setting->load(Yii::$app->request->post(),'');
      if($res && $setting->save() )
      {
        Yii::$app->session->setFlash('tips','save success');
      }
      else
      {
        Yii::$app->session->setFlash('tips',$setting->errorStr);
      }
    }

    $data = $setting->get();
    return $this->render('general',['data'=>$data]);
  }

  /**
   * 阅读设置
   */
  public function actionReading()
  {
    $setting = new ReadingSetting();

    //常规设置
    if( Yii::$app->request->isPost )
    {
      $setting->setScenario('set');
      $res = $setting->load(Yii::$app->request->post(),'');
      if($res && $setting->save() )
      {
        Yii::$app->session->setFlash('tips','save success');
      }
      else
      {
        Yii::$app->session->setFlash('tips',$setting->errorStr);
      }
    }

    $data = $setting->get();
    return $this->render('reading',['data'=>$data]);
  }

}
