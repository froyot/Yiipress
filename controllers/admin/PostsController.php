<?php
namespace app\controllers\admin;

use Yii;
use app\models\FormModel\PostsForm;
use app\models\TermTaxonomy;
use app\models\Posts;


/**
 * 文章管理
 */
class PostsController extends BaseController
{
	public $layout = "admin";
	/**
	 * 文章列表
	 * @author wangxianlong< xianlong.wang@dev-engine.com >
	 * @return null
	 */
	public function actionIndex()
	{
		// var_dump(Yii::$app->user->identity);die;
		$searchModel = new PostsForm();
        $this->otherParams['post_type'] = 'post';
        $dataProvider = $searchModel->search(
                                            Yii::$app->request->queryParams,
                                            $this->otherParams
                                            );

        $categoryModel = new TermTaxonomy();
        $categorys = $categoryModel->getCategorysByTree();
        $dates = Posts::getDates('post');

        // var_dump($otherParams);die;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categorys'=>$categorys,
            'dates'=>$dates,
            'otherParams'=>$this->otherParams
        ]);
	}

    /**
     * 添加文章
     * @author wangxianlong <xianlong.wang@dev-engine.com>
     * @return null
     */
    public function actionAdd()
    {
        $posts = new Posts();
        if( Yii::$app->request->isPost )
        {
            $res = $posts->load(Yii::$app->request->post(),'');
            if($res)
            {
                $categorys = Yii::$app->request->post('category_ids');
                $tagStrings = Yii::$app->request->post('tag_strings');
                $res = $posts->addNewPosts($categorys, $tagStrings);
                if( $res )
                {
                    $this->redirect(['admin/posts/update','id'=>$posts->primaryKey]);
                }
                else
                {
                    Yii::error($posts->errors);
                    Yii::$app->session->setFlash('tips', "数据保存错误");
                }
            }
            else
            {
                Yii::error($posts->errors);
                Yii::$app->session->setFlash('tips', "数据错误");
            }


        }
        $categoryModel = new TermTaxonomy();
        $categorys = $categoryModel->getCategorysByTree();
        return $this->render('add',['categorys'=>$categorys,'posts'=>$posts]);
    }

    /**
     * 更新文章
     * @param  int $id 文章id
     */
    public function actionUpdate($id)
    {
        $posts = Posts::findOne($id);
        if(!$posts)
        {
            throw new \yii\web\NotFoundHttpException;
        }
        if( Yii::$app->request->isPost )
        {
            $data = Yii::$app->request->post();
            $cats = Yii::$app->request->post('category_ids');
            $tagStrings = Yii::$app->request->post('tag_strings');
            $posts->attributes = $data;
            $res = $posts->updatePosts($cats, $tagStrings);
            if( $res !== false )
            {
                Yii::$app->session->setFlash('tips','保存成功');
            }
            else
            {
                Yii::$app->session->setFlash('tips','保存失败');
            }
            $cats = explode(',', $cats);

            $tagNames = explode(',', $tagStrings);
            $tags = [];
            foreach ($tagNames as $key => $tag) {
                $tags[] = ['name'=>$tag];
            }
        }
        else
        {
            //获取文章分类
            $cats = TermTaxonomy::getObjectTermTaxony($id,'category',true);
            $tags = TermTaxonomy::getObjectTermTaxony($id,'post_tag');
            //获取文章标签
        }
        // $posts = new Posts();
        $categoryModel = new TermTaxonomy();
        $categorys = $categoryModel->getCategorysByTree();
        return $this->render('update',[
                    'categorys'=>$categorys,
                    'posts'=>$posts,
                    '_tags'=>$tags,
                    '_cats'=>$cats
                ]);
    }

    public function actionDelete($id)
    {
        $post = Posts::findOne(['ID'=>$id]);
        if($post)
        {
            $post->delete();
        }
        return $this->redirect(['admin/posts/index']);
    }


}
