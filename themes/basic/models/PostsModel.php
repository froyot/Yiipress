<?php
namespace themeBase\models;
use Yii;
use yii\base\Model;
use app\models\Posts;
use app\models\TermTaxonomy;
use app\models\formModel\PostsForm;
use yii\helpers\Html;

class PostsModel extends Model
{
    public $model;
    function __construct($model)
    {
        $this->model = $model;
    }
    public function getTitle()
    {
        return Html::encode($this->model->post_title);
    }

    public function getAbstruct()
    {
        // return $this->model->post_content;
        if ( preg_match('/<!--more(.*?)?-->/', $this->model->post_content, $matches) ) {
            list($main, $extended) = explode($matches[0], $this->model->post_content, 2);
            $more_text = $matches[1];
        } else {
            $main = $this->model->post_content;
            $extended = '';
            $more_text = '';
        }

        //  leading and trailing whitespace.
        $main = preg_replace('/^[\s]*(.*)[\s]*$/', '\\1', $main);
        $extended = preg_replace('/^[\s]*(.*)[\s]*$/', '\\1', $extended);
        $more_text = preg_replace('/^[\s]*(.*)[\s]*$/', '\\1', $more_text);
        return $main;
    }

    public function getAuthor()
    {
        $user = UsersModel::getInfo($this->model->post_author);
        return $user->display_name;
    }
    public function getContent()
    {
        return $this->model->post_content;
    }

    public function getTags()
    {
        $tags = TermTaxonomy::getObjectTermTaxony($this->model->ID,'post_tag');
        return $tags;
    }

    public function getCategorys()
    {
        $categorys = TermTaxonomy::getObjectTermTaxony($this->model->ID);
        return $categorys;
    }

    public function getPost_type()
    {
        return $this->model->post_type;
    }
    public function getDate()
    {
        if(!isset(Yii::$app->params['SITE_OPTION']['date_format']))
            Yii::$app->params['SITE_OPTION']['date_format'] = 'Y-m-d';
        $format = Yii::$app->params['SITE_OPTION']['date_format'];
        $format = str_replace('n', 'm', $format);
        $format = str_replace('j', 'd', $format);
        $time = strtotime($this->model->post_date);
        $format = $format?$format:'Y-m-d';
        return date($format,($time));
    }


}
