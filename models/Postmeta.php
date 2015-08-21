<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%postmeta}}".
 *
 * @property string $meta_id
 * @property string $post_id
 * @property string $meta_key
 * @property string $meta_value
 */
class Postmeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%postmeta}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['meta_id', 'post_id'], 'integer'],
            [['meta_value'], 'safe'],
            [['meta_key'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'meta_id' => 'Meta ID',
            'post_id' => 'Post ID',
            'meta_key' => 'Meta Key',
            'meta_value' => 'Meta Value',
        ];
    }

    /**
     * 根据菜单的post_id获取网站菜单数组
     * @param  array $postIds 菜单post_id
     * @return array   菜单数组
     * [
     *     "title":菜单显示文字
     *     "url":菜单url,
     *     "type":菜单类型，page,category,custom
     *     "post_id":菜单post_id,
     *     "_key":page或category为名称，custom则是url
     *     "_key_id":page page_id, category 则是 category_id, custom 则是meta_value
     * ]
     */
    public function getMenus($postIds = '')
    {

        if(!$postIds)
        {
            return [];
        }
        if(is_array($postIds))
        {
            $postIds = implode("','", $postIds);

            $postIds = "'".$postIds."'";
        }

        $metaTbl = Postmeta::tableName();
        $sql = "SELECT meta_value,b.type,b.post_id from $metaTbl right join
                (SELECT post_id,meta_value as type from $metaTbl
                 where meta_key='_menu_item_object' and meta_value
                 in ('custom','category','page') and post_id in($postIds)
                 ) as b on $metaTbl.post_id=b.post_id and meta_key='_menu_item_object_id'";
        $query = Postmeta::findBySql($sql);
        $command = $query->createCommand();
        $menuInfos = $command->queryAll();

        $menus = [];
        foreach ($menuInfos as $key => $info)
        {
            $object = Posts::find()->where(['ID'=>$info['post_id']])->one();
            if( !$object )
                $index = 0;
            else
                $index = $object->menu_order;
            if( $info['type'] == 'page' || $info['type'] == 'custom' )
            {
                $object = Posts::findOne($info['meta_value']);
                if( !$object )
                    continue;
                $title = $object->post_title;
                if( $info['type'] == 'page' )
                {
                    $url = Url::to(['posts/detail','id'=>$info['meta_value']]);
                    $_key = $title;
                    $_key_id = $info['meta_value'];
                }
                else
                {
                    $url = Postmeta::findOne([
                        'meta_key'=>'_menu_item_url',
                        'post_id'=>$info['post_id']
                        ])->meta_value;
                    $_key = $url;
                    $_key_id = $info['post_id'];
                }
            }
            else
            {
                $term = Terms::findOne(['term_id'=>$info['meta_value']]);
                if( !$term )
                    continue;
                $title = $term->name;
                $url = Url::to(['posts/index','category'=>$info['meta_value']]);
                $_key = $title;
                $_key_id = $info['meta_value'];
            }
            $menus[] = [
            'title'=>$title,
            'url'=>$url,
            'type'=>$info['type'],
            'post_id'=>$info['post_id'],
            '_key'=>$_key,
            '_key_id'=>$_key_id
            ];
        }
        return $menus;
    }
}
