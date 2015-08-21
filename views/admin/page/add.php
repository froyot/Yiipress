<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\component\Ueditor;
?>

<div class="col-md-8">
    <div class="content-box-large">
        <div class="panel-heading">
            <div class="panel-title">Add New Page</div>
        </div>
        <div class="panel-body">
            <?php echo Html::beginForm('','post',['id'=>'post_form']);?>
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" placeholder="Enter The Title Here" type="text" name="post_title" value="<?php echo $page->post_title;?>">
                    </div>
                    <div class="form-group">
                        <?php
                        echo Ueditor::widget([
                            'name'=>'post_content',
                            'options'=>[
                                'default_content' =>$page->post_content,
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


