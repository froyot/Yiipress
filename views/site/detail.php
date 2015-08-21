<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = $content->title;
$this->registerMetaTag(array("name"=>"description","content"=>$content->description));//第一种  
if($content->type == 1)
	$this->params['breadcrumbs'][] = $this->title;
?>
<?= $content->content?>
