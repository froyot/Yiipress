<?php

namespace app\models\dataModel;

use Yii;
use yii\helpers\Url;
use app\models\TermRelationships;
use app\models\Postmeta;
use app\models\Options;
use app\models\Posts;

use app\models\TermTaxonomy;
use app\models\Terms;

class MenuData extends BaseModel
{

    public $post_id;
    public $key_id;
    public $type;
    public $title;
    public $value;
    public $url;

    public function rules()
    {
        return [
            [['post_id','key_id'],'number'],
            [['type','title'],'string'],
            [['post_id','key_id','type'],'required','on'=>['edit-category','edit-page']],
            [['post_id','value','type'],'required','on'=>['edit-title','edit-url']]
        ];
    }
    public function scenarios(){
        return [
            'edit-category'=>['post_id','key_id','type'],
            'edit-page'=>['post_id','key_id','type'],
            'edit-title'=>['post_id','value','type'],
            'edit-url'=>['post_id','value','type'],
            'add-category'=>['key_id','type'],
            'add-page'=>['key_id','type'],
            'add-custom'=>['title','url','type'],
            'default'=>['post_id','key_id','type','title'],
        ];
    }

    /**
     * 载入数据之后修正分类数据，分类菜单key_id 对应term表的term_id
     * @param  上传数据 $data [description]
     */
    public function afterLoad($data)
    {
        parent::afterLoad($data);
        if($this->type == 'category')
        {
            $termTaxonomy = TermTaxonomy::findOne(
                    ['term_taxonomy_id'=>$this->key_id]
                );
            if($termTaxonomy)
            {
                $this->key_id = $termTaxonomy->term_id;
            }
        }
    }

    /**
     * 获取网站菜单数组
     * @return array $menus
     */
    public function getMenus()
    {
        // $menus = null;
        $menus = unserialize(
                Yii::$app->cache->get(Yii::$app->params['MENU_CACHE'])
            );
        if($menus)
            return $menus;

        //获取主题信息
        $option = Options::findOne(['option_name'=>'template']);
        if(!$option)
        {
            $this->addError('tips','please install an theme');
            return [];
        }

        //获取当前主题的菜单
        $option = Options::findOne(
                ['option_name'=>'theme_mods_'.$option->option_value]
            );
        if(!$option)
        {
            return [];
        }

        $menuConfig = unserialize(
                $option->option_value
            );
        $menuId = $menuConfig['nav_menu_locations']['primary'];
        $objectIds = TermRelationships::getObjectIds($menuId);

        ///post_meta表中获取菜单、、、、fuck
        $menus = Postmeta::getMenus($objectIds);
        Yii::$app->cache->set(Yii::$app->params['MENU_CACHE'],serialize($menus));
        return $menus;
    }

    /**
     * 更新菜单信息
     * @return Boolean 是否成功
     */
    public function update()
    {
        if( $this->type == 'category' || $this->type == 'page' )
        {
            return $this->objectMenuUpdate();
        }
        elseif($this->type == 'title')
        {
            return $this->updateCoustomName();
        }
        elseif($this->type == 'url')
        {
            return $this->updateCoustomUrl();
        }

    }

    /**
     * 更新分类或页面菜单信息
     * @return boolean 是否成功
     */
    private function objectMenuUpdate()
    {
        $postmeta = Postmeta::findOne(
            [
            'post_id'=>$this->post_id,
            'meta_key'=>'_menu_item_object_id'
            ]);
        if(!$postmeta)
        {
            return false;
        }
        $checkMeta = Postmeta::findOne([
            'meta_key'=>'_menu_item_object_id',
            'meta_value'=>$this->key_id]);
        if($checkMeta && $postmeta->meta_id == $checkMeta->meta_id)
        {
            $this->addError('tips','this item is in menu');
            return null;
        }
        if($this->key_id!= $postmeta->meta_value)
        {
            $res = $this->updatePageAndCategoryMenuName();
            if($res === null)
                return null;
        }
        $postmeta->meta_value = $this->key_id;
        return $postmeta->save() && $this->afterSave();//更新之后，删除缓存
    }

    /**
     * 更新菜单名称
     * @return boolean 是否成功
     */
    private function updatePageAndCategoryMenuName()
    {
        $name = '';
        if( $this->type == 'page')
        {
            $page = Posts::findOne(['ID'=>$this->key_id]);
            if(!$page)
            {
                $this->addError('tips','page not exist');
                return null;
            }
            if($page)
            {
                $name = $page->post_title;
            }
        }
        else
        {
            $category = Terms::findOne(['term_id'=>$this->key_id]);

            if(!$category)
            {
                $this->addError('tips','page not exist');
                return null;
            }
            else
            {
                $name = $category->name;
            }
        }

        if($name!='')
        {
            $menuPost = Posts::findOne(['ID'=>$this->post_id]);
            if($menuPost)
            {
                $menuPost->post_title = $name;
                $menuPost->save();
            }
        }
        return true;
    }

    /**
     * 更新自定义菜单url
     */
    public function updateCoustomUrl()
    {
        $postmeta = Postmeta::findOne(
            [
            'post_id'=>$this->post_id,
            'meta_key'=>'_menu_item_url'
            ]);
        if(!$postmeta)
        {
            return false;
        }
        $postmeta->meta_value = $this->value;
        return $postmeta->save() && $this->afterSave();
    }

    /**
     * 更新自定义菜单名称
     */
    public function updateCoustomName()
    {
        $post = Posts::findOne(
            [
            'ID'=>$this->post_id,

            ]);
        if(!$post)
        {
            return false;
        }
        $post->post_title = $this->value;
        $post->setScenario('menu');
        $res = $post->save();
        if(!$res)
        {
            Yii::error($post->errors);
        }
        return  $res && $this->afterSave();
    }

    /**
     * 删除缓存
     */
    private function afterSave()
    {
        Yii::$app->cache->set(Yii::$app->params['MENU_CACHE'],null);
        return true;
    }

    /**
     * 获取菜单信息
     */
    public function getMenuInfo()
    {
        $menuInfo = TermTaxonomy::findOne(['taxonomy'=>'nav_menu']);
        if(!$menuInfo)
        {
            $terms = new Terms();
            $terms->name = 'mymenu';
            $terms->slug = 'mymenu';
            $terms->save();
            $menuInfo = new TermTaxonomy();
            $menuInfo->attributes = [
                'term_id'=>$terms->primaryKey,
                'taxonomy'=>'nav_menu',
                'count'=>'0'
            ];
            if( !$menuInfo->save() )
            {
                $this->addError('tips','add menu  error');
                return null;
            }

        }
        return $menuInfo;
    }

    /**
     * 添加菜单
     */
    public function add()
    {
        $menuInfo = $this->getMenuInfo();
        if(!$menuInfo)
            return;

        if($this->type == 'category')
        {
            $res = $this->addCategoryMenu($menuInfo);
        }
        elseif($this->type == 'page')
        {
            $res = $this->addPageMenu($menuInfo);
        }
        elseif($this->type == 'custom')
        {
            $res = $this->addCustomMenu($menuInfo);
        }
        $this->afterSave();
        return true && $res;
    }

    /**
     * 添加分类菜单
     * @param [type] $menuInfo [description]
     */
    private function addCategoryMenu($menuInfo)
    {
        //获取分类信息
        $termTaxonomy = TermTaxonomy::getCategory($this->key_id);
        if(!$termTaxonomy)
        {
            $this->addError('tips','category id error');
            return null;
        }
        $category = $termTaxonomy->terms;
        return $this->addMenuInfo($category->name,$menuInfo,$category->primaryKey);

    }

    /**
     * 添加分页菜单
     */
    private function addPageMenu($menuInfo)
    {
        //获取分类信息
        $page = Posts::findOne(['ID'=>$this->key_id]);
        if(!$page)
        {
            $this->addError('tips','page id error');
            return null;
        }
        $name = $page->post_title;
        return $this->addMenuInfo($name,$menuInfo,$page->ID);

    }

    /**
     * 添加自定义菜单
     */
    private function addCustomMenu($menuInfo)
    {
        return $this->addMenuInfo($this->title,$menuInfo,'0',$this->url);

    }

    /**
     * 添加菜单信息
     * @param string $name     菜单标题
     * @param Model $menuInfo 菜单信息
     * @param int $key_id   category id或page id
     * @param string $url      自定义的url
     */
    private function addMenuInfo($name,$menuInfo,$key_id = '0', $url='')
    {
        //添加post
        $post = new Posts();
        $post->attributes = [
            'post_title'=>$name,
            'post_type'=>'nav_menu_item',
            'post_content'=>$name,
        ];
        $res = $post->save();
        if(!$res)
        {
            $this->addError('tips','post add error');
            return null;
        }
        //添加relation
        $relation = new TermRelationships();
        $relation->attributes = [
            'object_id'=>$post->primaryKey,
            'term_taxonomy_id'=>$menuInfo->primaryKey
        ];
        $res = $relation->save();
        if($res)
        {
           $menuInfo->updateCounters(['count' => 1]);
        }
        else
        {
            $this->addError('tips','relation add error');
            return null;
        }
        //添加postmeta

        $postmeta = new Postmeta();
        $postmeta->attributes = [
            'post_id'=>$post->primaryKey,
            'meta_key'=>'_menu_item_object',
            'meta_value'=>$this->type,
        ];

        $postmeta->save();
        $postmeta = new Postmeta();
        $postmeta->attributes = [
            'post_id'=>$post->primaryKey,
            'meta_key'=>'_menu_item_object_id',
            'meta_value'=>$key_id?$key_id:$post->primaryKey,
        ];

        $postmeta->save();

        $postmeta = new Postmeta();
        $postmeta->attributes = [
            'post_id'=>$post->primaryKey,
            'meta_key'=>'_menu_item_url',
            'meta_value'=>$url,
        ];
        $postmeta->save();
        return true;
    }

}
