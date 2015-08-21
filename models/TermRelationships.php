<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%term_relationships}}".
 *
 * @property string $object_id
 * @property string $term_taxonomy_id
 * @property integer $term_order
 */
class TermRelationships extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%term_relationships}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'term_taxonomy_id'], 'required'],
            [['object_id', 'term_taxonomy_id', 'term_order'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'object_id' => 'Object ID',
            'term_taxonomy_id' => 'Term Taxonomy ID',
            'term_order' => 'Term Order',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getTermTaxonomy()
    {
        return $this->hasOne(
                TermTaxonomy::className(),
                ['term_taxonomy_id'=>'term_taxonomy_id']
            );
    }
    /**
     * 根据分类或者tag,菜单获取该标签或分类下的内容
     * @param  int $term_taxonomy_id 分类，tag,菜单id
     * @return array   内容id,以数字为键值
     */
    public function getObjectIds($term_taxonomy_id)
    {
        $objs = TermRelationships::find()
                ->where(['term_taxonomy_id'=>$term_taxonomy_id])
                ->select('object_id')->asArray()->all();
        $ids = [];
        foreach ($objs as $key => $obj)
        {
            $ids[] = $obj['object_id'];
        }
        return $ids;
    }

    /**
     * 内容更新后，删除不关联的tag,分类
     * @param  string $tags         tag id 字符串，逗号分隔
     * @param  string $categoryIds  category id 字符串，逗号分隔
     * @param  int $object_id       内容id
     * @return null
     */
    public function deleteNotUsedTaxono($tags, $categoryIds, $object_id)
    {
        if( is_string( $tags ) )
        {
            $tags = explode(',', $tags);
        }
        else
        {
            $tags = [];
        }

        if( is_string( $categoryIds ) )
        {
            $categoryIds = explode(',', $categoryIds);
        }
        else
        {
            $categoryIds = [];
        }

        //获取对象原有的tag
        $taxonomyTbl = TermTaxonomy::tableName();
        $termTbl = Terms::tableName();
        $relationTbl = TermRelationships::tableName();
        $terms = TermRelationships::find()
                        ->joinWith('termTaxonomy')
                        ->joinWith('termTaxonomy.terms')
                        ->where([
                            "$relationTbl.object_id"=>$object_id
                        ])
                        ->select([
                            "$relationTbl.term_taxonomy_id",
                            "$termTbl.name,$taxonomyTbl.taxonomy"])
                        ->all();
        $deleteIds = [];
        foreach ($terms as $key => $tag)
        {
            if( $tag->termTaxonomy->taxonomy == 'post_tag' &&
                !in_array($tag->termTaxonomy->terms->name, $tags) )
            {
                $deleteIds[] = $tag['term_taxonomy_id'];
            }

            if( $tag->termTaxonomy->taxonomy == 'category' &&
                !in_array($tag->term_taxonomy_id, $categoryIds))
            {
                $deleteIds[] = $tag['term_taxonomy_id'];
            }
        }

        TermRelationships::deleteAll([
            'object_id'=>$object_id,
            'term_taxonomy_id'=>$deleteIds
            ]);
    }
}
