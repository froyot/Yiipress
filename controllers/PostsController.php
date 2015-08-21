<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use themeBase\models\SiteModel;


class PostsController extends BasicController
{
    /**
     * 文章列表
     */
    public function actionIndex()
    {
        $content = SiteModel::getSiteIndexContent($this,'posts');
        return $this->render('index',['content'=>$content]);
    }

    /**
     * 详情
     * @param  int $id 文章id
     */
    public function actionDetail($id)
    {
        list($dataModel,$content) = SiteModel::getPostDetail($this,$id);
        // var_dump($dataModel->post_type);die;
        if($dataModel->post_type == 'post')
            $view = 'detail';
        else
            $view = 'page';
        if($content)
        {

            return $this->render($view,['content'=>$content,'data'=>$dataModel]);
        }

    }
}
