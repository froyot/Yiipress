<?php
namespace app\controllers\admin;
use Yii;
use app\models\FormModel\PostsForm;
use app\models\TermTaxonomy;
use app\models\Posts;
use app\models\dataModel\ThemeData;
use app\models\dataModel\ReadingSetting;
use app\models\dataModel\MenuData;
use app\models\TermRelationships;
use app\models\Postmeta;


class ThemeController extends BaseController
{

    /**
     * 主题选择
     * @return [type] [description]
     */
    public function actionThemes()
    {
        $theme = new ThemeData();
        $data = $theme->get();
        $current = ThemeData::getCurrentTheme();
        return $this->render('themes',['themes'=>$data,'current'=>$current]);
    }

    /**
     * 菜单管理
     */
    public function actionMenu()
    {
        if(Yii::$app->request->isPost)
        {
            $type = Yii::$app->request->post('type');
            $params = Yii::$app->request->post();
            if($type == 'category')
            {
                $params['key_id'] = $params['cat_key_id'];
            }
            elseif($type == 'page')
            {
                $params['key_id'] = $params['page_key_id'];
            }
            $menuData = new MenuData();
            $menuData->setScenario('add-'.$type);

            $res = $menuData->load($params,'');
            if($res && $menuData->add())
            {
                Yii::$app->session->setFlash('tips','add success');
            }
            else
            {
                Yii::$app->session->setFlash('tips','add error'.$menuData->errorStr);
            }
        }
        $data = MenuData::getMenus();
        $categoryModel = new TermTaxonomy();
        $categorys = $categoryModel->getCategorysByTree();
        $pages = Posts::getAllPage();
        // var_dump($pages);die;
        return $this->render('menu',
            [
            'menus'=>$data,
            'categorys'=>$categorys,
            'pages'=>$pages
            ]);
    }

    /**
     * 删除菜单
     */
    public function actionDeleteMenu($id)
    {
        //检查是否是菜单
        $post = Posts::findOne(['post_type'=>'nav_menu_item','ID'=>$id]);
        if(!$post)
        {
            Yii::$app->session->setFlash('tips','the menu not exist');
        }
        else
        {
            //删除relation
            TermRelationships::deleteAll(['object_id'=>$id]);
            //删除meta
            Postmeta::deleteAll(['post_id'=>$id]);
            //删除post
            $post->delete();
            Yii::$app->cache->set(Yii::$app->params['MENU_CACHE'],null);
        }
        return $this->redirect(['admin/theme/menu']);
    }
}
