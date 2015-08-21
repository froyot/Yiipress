<?php

namespace app\models;

use Yii;

interface PostsInterface
{
    /**
     * 获取文章列表
     * @return array 文章数组
     * [
     *     'post_title',
     *     'post_content',
     *     'post_author',
     *     'post_date',
     *     'post_password',
     *     'comment_count',
     *     'category'=>['id'=>'name'],
     *     'tags'=>['id'=>'name'],
     *     'author_name'
     *
     * ]
     */
    public function getPosts();

    //文章分类
    public function getPostCategory();

    //文章标签
    public function getPostTags();

    //文章摘要
    public function getPostAbstract();


    //文章评论
    public function getPostComment();


    //文章评论


}
