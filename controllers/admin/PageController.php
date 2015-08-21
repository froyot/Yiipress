<?php
namespace app\controllers\admin;
use Yii;
use app\models\FormModel\PostsForm;
use app\models\TermTaxonomy;
use app\models\Posts;


class PageController extends BaseController
{
  public function actionIndex()
  {
    $dates = Posts::getDates('page');
    $searchModel = new PostsForm();
    $this->otherParams['post_type'] = 'page';
    $dataProvider = $searchModel->search(
                                          Yii::$app->request->queryParams,
                                          $this->otherParams
                                        );
    return $this->render('index',
                          [
                            'dataProvider'=>$dataProvider,
                            'dates'=>$dates
                          ]
                        );
  }


    /**
     * 添加页面
     * @author wangxianlong <xianlong.wang@dev-engine.com>
     * @return null
     */
    public function actionAdd()
    {
        $page = new Posts();
        if( Yii::$app->request->isPost )
        {
            $res = $page->load(Yii::$app->request->post(),'');
            if($res)
            {
                $res = $page->addPage();
                if( $res )
                {
                    $this->redirect(['admin/page/update','id'=>$page->primaryKey]);
                }
                else
                {
                    Yii::error($page->errors);
                    Yii::$app->session->setFlash('tips', "数据保存错误");
                }
            }
            else
            {
                Yii::error($page->errors);
                Yii::$app->session->setFlash('tips', "数据错误");
            }


        }
        return $this->render('add',['page'=>$page]);
    }

    /**
     * 更新页面
     * @author wangxianlong <xianlong.wang@dev-engine.com>
     * @return null
     */
    public function actionUpdate($id)
    {
        $page = Posts::findOne(['ID'=>$id,'post_type'=>'page']);
        if(!$page)
        {
          throw new \yii\web\NotFoundHttpException;
        }
        if( Yii::$app->request->isPost )
        {
            $res = $page->load(Yii::$app->request->post(),'');
            if($res)
            {
                $res = $page->updatePage();
                if( $res )
                {
                    Yii::$app->session->setFlash('tips', "数据保存成功");
                }
                else
                {
                    Yii::error($page->errors);
                    Yii::$app->session->setFlash('tips', "数据保存错误");
                }
            }
            else
            {
                Yii::error($page->errors);
                Yii::$app->session->setFlash('tips', "数据错误");
            }


        }
        return $this->render('update',['page'=>$page]);
    }

    public function actionDelete()
    {
      $id = Yii::$app->request->get('id');
      $res = Posts::deleteOne($id);
      return $this->redirect(['admin/page/index']);
    }

}

