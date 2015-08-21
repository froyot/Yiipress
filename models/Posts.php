<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%posts}}".
 *
 * @property string $ID
 * @property string $post_author
 * @property string $post_date
 * @property string $post_date_gmt
 * @property string $post_content
 * @property string $post_title
 * @property string $post_excerpt
 * @property string $post_status
 * @property string $comment_status
 * @property string $ping_status
 * @property string $post_password
 * @property string $post_name
 * @property string $to_ping
 * @property string $pinged
 * @property string $post_modified
 * @property string $post_modified_gmt
 * @property string $post_content_filtered
 * @property string $post_parent
 * @property string $guid
 * @property integer $menu_order
 * @property string $post_type
 * @property string $post_mime_type
 * @property string $comment_count
 */
class Posts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%posts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_author', 'post_parent', 'menu_order', 'comment_count'], 'integer'],
            [['post_date', 'post_date_gmt', 'post_modified', 'post_modified_gmt'], 'safe'],
            [['post_content', 'post_title'], 'required','on'=>['post']],
            [['post_title'], 'required','on'=>['menu']],
            [['post_content', 'post_title', 'post_excerpt', 'to_ping', 'pinged', 'post_content_filtered'], 'string'],
            [['post_status', 'comment_status', 'ping_status', 'post_password', 'post_type'], 'string', 'max' => 20],
            [['post_name'], 'string', 'max' => 200],
            [['guid'], 'string', 'max' => 255],
            [['post_mime_type'], 'string', 'max' => 100]
        ];
    }

    // public function scenarios(){
    //     return [
    //         'menu'=>['post_title','post_author','post_date','post_status',''],
    //         'edit-page'=>['post_id','key_id','type'],
    //         'edit-title'=>['post_id','value','type'],
    //         'edit-url'=>['post_id','value','type'],
    //         'default'=>['post_id','key_id','type','name'],
    //     ];
    // }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'post_author' => 'Post Author',
            'post_date' => 'Post Date',
            'post_date_gmt' => 'Post Date Gmt',
            'post_content' => 'Post Content',
            'post_title' => 'Post Title',
            'post_excerpt' => 'Post Excerpt',
            'post_status' => 'Post Status',
            'comment_status' => 'Comment Status',
            'ping_status' => 'Ping Status',
            'post_password' => 'Post Password',
            'post_name' => 'Post Name',
            'to_ping' => 'To Ping',
            'pinged' => 'Pinged',
            'post_modified' => 'Post Modified',
            'post_modified_gmt' => 'Post Modified Gmt',
            'post_content_filtered' => 'Post Content Filtered',
            'post_parent' => 'Post Parent',
            'guid' => 'Guid',
            'menu_order' => 'Menu Order',
            'post_type' => 'Post Type',
            'post_mime_type' => 'Post Mime Type',
            'comment_count' => 'Comment Count',
        ];
    }
    public function getDates($postType = 'post')
    {
        $res = Posts::find()->select(["post_date"=>"date_format(post_date,'%Y-%m')"])
                ->distinct(true)
                ->where(['post_type'=>$postType])
                ->asArray()
                ->all();
        return $res;
    }

    /**
     * 新增文章
     * @param  string $categorys 文章勾选的分类，逗号分隔
     * @param  string $tagString 填写的标签内容，多个标签逗号分隔
     */
    public function addNewPosts($categorys = '1', $tagString = '')
    {
        $this->post_type = 'post';
        $this->post_author = Yii::$app->user->id;
        $this->post_date = date('Y-m-d H:i:s');
        $this->post_status = 'publish';
        $this->comment_status = 'open';
        $this->post_modified = date('Y-m-d H:i:s');
        $res = $this->save();

        if( $res )
        {
            //添加分类关联数据
            //
            $categoryIds = TermTaxonomy::getExitCategory($categorys);
            $ids = TermTaxonomy::getTags($tagString);
            $relationIds = array_merge($categoryIds, $ids);
            $TermRelationships = [];
            foreach ($relationIds as $key => $id)
            {
                if(TermRelationships::findOne(['term_taxonomy_id'=>$id,'object_id'=>$this->primaryKey]))
                {
                    continue;
                }
                $termRelationships = new TermRelationships();
                $termRelationships->object_id = $this->primaryKey;
                $termRelationships->term_taxonomy_id = $id;
                $termRelationships->term_order = 0;
                $TermRelationships[] = $termRelationships->toArray();
            }
            if($TermRelationships)
            {
            //批量添加
            Yii::$app->db->createCommand()->batchInsert(
                        TermRelationships::tableName(),
                        ['object_id', 'term_taxonomy_id','term_order'],
                        $TermRelationships
                    )->execute();
            }
            //更新分类文章数目
            TermTaxonomy::updateAllCounters(
                                ['count'=>1],
                                ['term_taxonomy_id'=>$relationIds]);

        }

        return $res;
    }

    /**
     * 新增页面数据
     */
    public function addPage()
    {
        $this->post_type = 'page';
        $this->post_author = Yii::$app->user->id;
        $this->post_date = date('Y-m-d H:i:s');
        $this->post_status = 'publish';
        $this->comment_status = 'open';
        $this->post_modified = date('Y-m-d H:i:s');
        $res = $this->save();
        return $res;
    }

    /**
     * 更新页面数据
     */
    public function updatePage()
    {
        $this->post_type = 'page';
        $this->post_author = Yii::$app->user->id;
        $this->post_modified = date('Y-m-d H:i:s');
        $res = $this->save();
        return $res;
    }

    /**
     * 更新文章
     * @param  string $categorys 更新文章勾选的分类，逗号分隔
     * @param  string $tagString 填写的标签内容，多个标签逗号分隔
     */
    public function updatePosts($categorys = '1', $tagString = '')
    {
        $this->post_type = 'post';
        $this->post_author = Yii::$app->user->id;
        $this->post_modified = date('Y-m-d H:i:s');
        $res = $this->save();
        if( $res )
        {
            //添加分类关联数据
            //
            $categoryIds = TermTaxonomy::getExitCategory($categorys);
            $ids = TermTaxonomy::getTags($tagString);
            $relationIds = array_merge($categoryIds, $ids);
            $TermRelationships = [];
            foreach ($relationIds as $key => $id)
            {
                if(TermRelationships::findOne([
                    'term_taxonomy_id'=>$id,
                    'object_id'=>$this->primaryKey]))
                {
                    continue;
                }
                $termRelationships = new TermRelationships();
                $termRelationships->object_id = $this->primaryKey;
                $termRelationships->term_taxonomy_id = $id;
                $termRelationships->term_order = 0;
                $TermRelationships[] = $termRelationships->toArray();
            }

            if($TermRelationships)
            {

            //批量添加
            Yii::$app->db->createCommand()->batchInsert(
                        TermRelationships::tableName(),
                        ['object_id', 'term_taxonomy_id','term_order'],
                        $TermRelationships
                    )->execute();
            }

            //去除不存在的标签,分类
            TermRelationships::deleteNotUsedTaxono($tagString,$categorys,$this->primaryKey);
            //更新分类文章数目
            TermTaxonomy::updateAllCounters(
                                ['count'=>1],
                                ['term_taxonomy_id'=>$relationIds]);

        }

        return $res;
    }

    public function deleteOne($id)
    {
        $posts = Posts::findOne($id);
        if( !$posts )
        {
            return;
        }
        $res = $posts->delete();
        //删除relation表
        TermRelationships::deleteAll(['object_id'=>$id]);
        return $res;
    }

    public function getAllPage()
    {
        $pages = Posts::find()
        ->where([
            'post_type'=>'page',
            'post_status'=>'publish'
            ])
        ->select(['post_title','ID'])
        ->all();
        if(!$pages)
        {
            return [];
        }
        return $pages;
    }
}
