<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%term_taxonomy}}".
 *
 * @property string $term_taxonomy_id
 * @property string $term_id
 * @property string $taxonomy
 * @property string $description
 * @property string $parent
 * @property string $count
 */
class TermTaxonomy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%term_taxonomy}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['taxonomy', 'term_id'], 'required'],
            [['term_taxonomy_id', 'term_id', 'parent', 'count'], 'integer'],
            [['description'], 'string'],
            [['taxonomy'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'term_taxonomy_id' => 'Term Taxonomy ID',
            'term_id' => 'Term ID',
            'taxonomy' => 'Taxonomy',
            'description' => 'Description',
            'parent' => 'Parent',
            'count' => 'Count',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getTerms()
    {
        return $this->hasOne(Terms::className(),['term_id'=>'term_id']);
    }

    /**
     * 获取某个分类
     * @param  int $id 分类名称
     * @return Model   TermTaxonomy Model
     */
    public static function getCategory($id)
    {
        return TermTaxonomy::findOne([
                        'taxonomy'=>'category',
                        'term_taxonomy_id'=>$id
                        ]);
    }

    /**
     * 添加分类
     * @param string  $name 名称
     * @param int $pid  父级分类id
     * @param string $slug 分类slug
     * @param string $description 描述
     */
    public static function addCategory($name,$pid = 0, $slug = '', $description = '')
    {
        return TermTaxonomy::addData($name, $pid, $slug, $description, 'category');
    }

    /**
     * 以树状结构获取分类数据
     * @param  integer $parent 父级菜单
     * @param  integer $level  层级
     * @return array   分类数组
     */
    public function getCategorysByTree($parent=0,$level=-1)
    {
        $level++;
        $category = TermTaxonomy::find()->from([
                        TermTaxonomy::tableName().' as a',
                        Terms::tableName().' as b'
                        ])
                    ->where([
                        'a.parent'=>$parent,
                        'a.taxonomy'=>'category',
                        ])
                    ->andOnCondition('a.term_id=b.term_id')
                    ->select(['a.term_taxonomy_id','a.parent','a.count','b.name'])
                    ->asArray()->all();
        $categoryTmp = [];
        foreach ($category as $key => $item)
        {
            $item['name'] = str_repeat ("&nbsp;",2*$level). $item['name'];
            $categoryTmp[] = $item;
            $childCats = $this->getCategorysByTree(
                                                $item['term_taxonomy_id'],
                                                $level
                                            );

            foreach ($childCats as $cat)
            {
                $categoryTmp[] = $cat;
            }
        }
        return $categoryTmp;
    }

    /**
     * 根据传入的id字符串过滤分类的id
     * @param  string $ids id字符串
     * @return array 分类id数组
     */
    public static function getExitCategory($ids)
    {
        if(is_string($ids))
        {
            $ids = explode(',', $ids);
        }
        $termTaxonomys = TermTaxonomy::find()
                    ->where([
                        'term_taxonomy_id'=>$ids,
                        'taxonomy'=>'category',
                        ])
                    ->select('term_taxonomy_id')
                    ->asArray()
                    ->all();
        $ids = [];
        foreach ($termTaxonomys as $key => $item)
        {
            $ids[] = $item['term_taxonomy_id'];
        }
        return $ids;
    }

    /**
     * 根据内容上传的tag名称添加并获取tag的id
     * @param string $tags tag名称，多个以逗号分隔
     * @return  array tag id 数组
     */
    public static function getTags($tags)
    {
        if( is_string( $tags ) )
        {
            $tags = explode(',', $tags);
        }
        $ids = [];
        if( !$tags || !is_array($tags))
            return $ids;
        foreach ($tags as $key => $tag)
        {
            if( !$tag )
                continue;
           //先查询是否有该标签
           $term = Terms::findOne(['name'=>$tag]);
           if(!$term)
           {
                $terms = new Terms();
                $terms->name = $tag;
                $terms->slug = urlencode($tag);
                $terms->term_group = 0;
                if( $terms->save() )
                {
                    $termTaxonomy = new TermTaxonomy();
                    $termTaxonomy->term_id = $terms->primaryKey;
                    $termTaxonomy->taxonomy = 'post_tag';
                    $termTaxonomy->count = 0;
                    $termTaxonomy->save();
                }
           }
           else
           {
                $termTaxonomy = TermTaxonomy::findOne(['term_id'=>$term->term_id]);
           }
           if( $termTaxonomy )
            $ids[] = $termTaxonomy->primaryKey;
        }
        return $ids;
    }

    /**
     * 添加tag
     * @param string $name        tag名称
     * @param string $slug        slug
     * @param string $description 描述
     */
    public static function addTag($name, $slug = '', $description = '')
    {
        return TermTaxonomy::addData($name, 0, $slug, $description, 'post_tag');
    }

    /**
     * 添加termTaxonomy
     * @param [type] $name        名称
     * @param [type] $pid         pid
     * @param [type] $slug        slug
     * @param string $description 描述
     * @param [type] $taxonomy    类型
     */
    public static function addData($name, $pid, $slug, $description = '', $taxonomy)
    {
        $terms = new Terms();
        $terms->name = $name;
        $terms->slug = $slug?urlencode($slug):urlencode($name);
        $terms->term_group = 0;
        if ( $terms->save() )
        {
            $term_id = $terms->primaryKey;
            $termTaxonomy = new TermTaxonomy();
            $termTaxonomy->taxonomy = $taxonomy;
            $termTaxonomy->term_id = $term_id;
            $termTaxonomy->parent = $pid;
            $termTaxonomy->count = 0;
            $termTaxonomy->description = $description;
            if ( $termTaxonomy->save() )
            {
                return true;
            }
            else
            {
                Yii::error($termTaxonomy->errors);
                return false;
            }
        }
        else
        {

            Yii::error($terms->errors);
        }
        return false;
    }

    /**
     * 获取内容相关的分类或tag信息
     * @param  int  $object_id 内容id
     * @param  string  $type      'category','post_tag'
     * @param  boolean $onlyId    是否只返回id数组
     * @return 数组            tag或分类数组
     */
    public function getObjectTermTaxony($object_id,$type = 'category',$onlyId = false)
    {
        $taxonyTabl = TermTaxonomy::tableName();
        $relationTbl = TermRelationships::tableName();
        $termTbl = Terms::tableName();
        $select = ['$taxonyTabl.term_taxonomy_id'];
        $query = TermRelationships::find()
                            ->joinWith('termTaxonomy')
                            ->joinWith('termTaxonomy.terms')
                            ->where([
                                "$taxonyTabl.taxonomy"=>$type,
                                "$relationTbl.object_id"=>$object_id
                                ])
                            ->select([
                                "$termTbl.name",
                                "$taxonyTabl.term_taxonomy_id"
                                ]);
        $command = $query->createCommand();
        $rows = $command->queryAll();
        if($onlyId)
        {   $ids = [];
            foreach ($rows as $key => $item)
            {
                $ids[] = $item['term_taxonomy_id'];
            }
            $rows = $ids;
        }
        return $rows;
    }
}
