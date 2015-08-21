<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\component\Ueditor;

?>

<script type="text/javascript">
var addCategoryUrl = "<?php echo Url::to(['admin/ajax/add-category']);?>";
</script>
<div class="col-md-8">
    <div class="content-box-large">
        <div class="panel-heading">
            <div class="panel-title">Add New Post</div>
        </div>
        <div class="panel-body">
            <?php echo Html::beginForm('','post',['id'=>'post_form']);?>
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" placeholder="Enter The Title Here" type="text" name="post_title" value="<?php echo $posts->post_title;?>">
                    </div>
                    <div class="form-group">
                        <?php
                        echo Ueditor::widget([
                            'name'=>'post_content',
                            'value'=>$posts->post_content,
                            'options'=>[
                                'initialFrameHeight'=>400,
                                'id'=>'txtContent',
                                'focus'=>true,
                                'imageBlockLine'=>'center',
                                'toolbars'=> [
                                    ['fullscreen', 'source', 'undo', 'redo','insertcode',
                                      'bold','justifyleft','link','simpleupload'
                                    ]
                                ]
                            ]

                        ]);
                        ?>
                        <!-- <textarea class="form-control" placeholder="Textarea" rows="3"></textarea> -->
                    </div>
                </fieldset>
                <div>
                    <input name="category_ids" type="hidden" id="category_ids"/>
                    <input name="tag_strings" type="hidden" id="tag_strings"/>
                </div>
            <?php echo Html::endForm();?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title" style="float:none;">
            <a data-toggle="collapse" data-parent="#accordion"
              href="#collapsePublish">
              Publish
            </a>
          </h3>
        </div>
        <div id="collapsePublish" class="panel-collapse collapse in">
          <div class="panel-body">
                <div class="form-group">
                    <button class="btn btn-primary" id="publish">Publish</button>
                </div>
          </div>
        </div>
    </div>
</div>
<?php if($posts->post_type == 'post'):?>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title" style="float:none;">
            <a data-toggle="collapse" data-parent="#accordion"
              href="#collapseOne">
              Category
            </a>
          </h3>
        </div>
        <div id="collapseOne" class="panel-collapse collapse collapsed">
          <div class="panel-body" id="category_check_content">
            <?php foreach ($categorys as $key => $item):?>
            <div class="checkbox">
            <label>
                <input type="checkbox" value="<?php echo $item['term_taxonomy_id'];?>"
                <?php if(in_array($item['term_taxonomy_id'], $_cats)):?> checked="checked"<?php endif;?>
                >
            <?php echo $item['name'];?></label>
            </div>
            <?php endforeach;?>

          </div>
        </div>
        <div class="panel-footer">
            <a id="show_add_category">
              + Add New Category
            </a>
            <div id="add_category_content" class="hidden">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="" name="category"/>
                </div>
                <div class="form-group">
                    <select class="form-control" id="categorys" name="otherParams[category]">
                        <option value="0">无</option>
                        <?php foreach ($categorys as $key => $item):?>
                        <option value="<?php echo $item['term_taxonomy_id'];?>">
                            <?php echo $item['name'];?>
                        </option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" id="add_category">Add</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title" style="float:none;">
            <a data-toggle="collapse" data-parent="#accordion"
              href="#collapseTags">
              Tags
            </a>
          </h3>
        </div>
        <div id="collapseTags" class="panel-collapse collapse collapsed">
          <div class="panel-body">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="" name="tag"/>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" id="add_tag_button">Add</button>
                </div>
                <div class="form-group" id="tag_check">
                    <?php foreach($_tags as $mtag):?>
                    <span class='tag-item'><a class='delete-tag'>X</a><label><?php echo $mtag['name'];?></label></span>
                    <?php endforeach;?>
                </div>
          </div>
        </div>
    </div>
</div>
<?php endif;?>

