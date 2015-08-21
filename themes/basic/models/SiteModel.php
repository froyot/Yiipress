<?php
namespace themeBase\models;
use Yii;
use app\models\Posts;
use app\models\formModel\PostsForm;

class SiteModel
{
	//获取网站首页设置，并输出文章首页内容
	public function getSiteIndexContent($controller,$config = null)
	{
		$content = '';
        if($config == null)
		  $config = SiteModel::getSiteConfig();
		if($config == 'posts')
		{
			//输出文章列表
			//$content = $controller->render('//public/posts');
			$searchModel = new PostsForm();
			$params = Yii::$app->request->queryParams;
            $otherParams = Yii::$app->request->queryParams;
            $otherParams['post_type'] = 'post';
      		$dataProvider = $searchModel->search($params,$otherParams);
			$content = $controller->renderAjax('//public/posts',[
            // 'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		}
		elseif($config == 'post')
		{
			//输出单篇文章
			$id = 3;
			$post = Posts::findOne($id);
			if(!$post)
			{
				echo '404';
			}
			else
			{
				$content = $controller->renderAjax('//public/post',['post'=>$post]);
			}

		}
		elseif($config == 'page')
		{
			//输出页面
		}
		return $content;
	}

	public function getSiteConfig()
	{
        if(!isset(Yii::$app->params['SITE_OPTION']['show_on_front']))
            return 'posts';
		if( Yii::$app->params['SITE_OPTION']['show_on_front'] == 'page')
			return 'page';
		else
			return 'posts';
	}

	public function getPostDetail($controller,$id)
	{
        $model = Posts::findOne($id);
        if(!$model)
        {
            return null;
        }
        $postsModel = new PostsModel($model);
        if($model->post_type == 'posts')
        {

            $content = $controller->renderAjax('//public/post',['post'=>$postsModel]);
            return [$postsModel,$content];
        }
        else
        {
            $content = $controller->renderAjax('//public/page',['page'=>$model]);

            return [$postsModel,$content];
        }


	}




}
