<?php
namespace app\controllers\admin;
use Yii;
use app\models\Terms;
use app\models\TermTaxonomy;
use app\models\formModel\TermTaxonomyForm;

class TagsController extends BaseController
{
  /**
   * tag 管理
   * @return [type] [description]
   */
  public function actionIndex()
  {
    $tag = [
                  'name'=>'',
                  'slug'=>'',
                  'description'=>'',
                ];
    if( Yii::$app->request->isPost )
    {
      $tag['name'] = $this->post('name');
      $tag['slug'] = $this->post('slug');
      $tag['description'] = $this->post('description');
      $res = TermTaxonomy::addTag(
                                    $tag['name'],
                                    $tag['slug'],
                                    $tag['description']
                                  );
      if( $res )
      {
        Yii::$app->session->setFlash('tips',' add tag success !');
      }
      else
      {
        Yii::$app->session->setFlash('tips',' add tag error !');
      }

    }
    $tagModel = new TermTaxonomy();

    $this->otherParams['taxonomy'] = 'post_tag';
    $searchModel = new TermTaxonomyForm();
    $dataProvider = $searchModel->search(
                                            Yii::$app->request->queryParams,
                                             $this->otherParams
                                            );

    return $this->render('index',['tag'=>$tag,'dataProvider'=>$dataProvider]);
  }
  public function actionDelete($id)
  {
    $res = TermTaxonomy::deleteItem($id);
    return $this->redirect(['admin/tags/index']);
  }
}

