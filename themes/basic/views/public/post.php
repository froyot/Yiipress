<?php
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = $post->title;
?>
<style type="text/css">
article .post-meta{
    font-size: 0.8em;
    margin-bottom: 0.5em;
    margin-top: 0.5em;
}
</style>
    <!-- Post Content -->
    <article>

            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                	<?php echo $post->content;?>
                    <hr>
                    <p class="post-meta">Posted by <a href="#"><?=$post->author;?></a><?=$post->date;?></p>
                    <p class="post-meta">Category:

                    <?php foreach($post->categorys as $category):?>
                        <a href="<?=Url::to(['posts/index','category'=>$category['term_taxonomy_id']]);?>"><?=$category['name'];?></a>&nbsp;&nbsp;
                    <?php endforeach;?>
                    </p>
                    <?php if($post->tags):?>
                    <p class="post-meta">WithTag:
                        <?php foreach($post->tags as $tag):?>
                            <a href="<?=Url::to(['posts/index','tag'=>$tag['term_taxonomy_id']]);?>"><?=$tag['name'];?></a>&nbsp;&nbsp;
                        <?php endforeach;?>
                    </p>
                    <?php endif;?>
                </div>

            </div>

    </article>





