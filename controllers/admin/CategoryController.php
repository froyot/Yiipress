<?php
namespace app\controllers\admin;
use Yii;
use app\models\Terms;
use app\models\TermTaxonomy;
use app\models\formModel\TermTaxonomyForm;

class CategoryController extends BaseController
{
  public function actionIndex()
  {
    $category = [
                  'name'=>'',
                  'parent'=>'',
                  'slug'=>'',
                  'description'=>'',
                ];
    if( Yii::$app->request->isPost )
    {
      $category['name'] = $this->post('name');
      $category['parent'] = $this->post('parent');
      $category['slug'] = $this->post('slug');
      $category['description'] = $this->post('description');
      $res = TermTaxonomy::addCategory(
                                        $category['name'],
                                        $category['parent'],
                                        $category['slug'],
                                        $category['description']
                                      );
      if( $res )
      {
        Yii::$app->session->setFlash('tips',' add category success !');
      }
      else
      {
        Yii::$app->session->setFlash('tips',' add category error !');
      }
    }


    $categoryModel = new TermTaxonomy();
    $categorys = $categoryModel->getCategorysByTree();
    $this->otherParams['taxonomy'] = 'category';
    $searchModel = new TermTaxonomyForm();
    $dataProvider = $searchModel->search(
                                          Yii::$app->request->queryParams,
                                          $this->otherParams
                                        );

    return $this->render('index',
                          [
                            'category'=>$category,
                            'categorys'=>$categorys,
                            'dataProvider'=>$dataProvider
                          ]
                        );
  }

  public function actionDelete($id)
  {
    $res = TermTaxonomy::deleteItem($id);
    return $this->redirect(['admin/category/index']);
  }
}

