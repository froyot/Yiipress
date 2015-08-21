<?php
use yii\helpers\Url;
use yii\helpers\Html;
use themeBase\models\PostsModel;

$postsModel = new PostsModel($model);
?>
<div class="post-preview">
    <a href="<?php echo Url::to(['posts/detail','id'=>$model['ID']]);?>">
        <h3 class="post-title">
            <?=$postsModel->title;?>
        </h3>
        <div class="post-subtitle">
             <?=$postsModel->abstruct;?>
        </div>
    </a>
    <p class="post-meta">Posted by <a href="#"><?=$postsModel->author;?></a><?=$postsModel->date;?></p>
</div>
<hr>
