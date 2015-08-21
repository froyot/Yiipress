<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
?>

<div class="col-md-4">
    <div class="content-box-large">
        <div class="panel-heading">
            <div class="panel-title">Add New Tag</div>
        </div>
        <div class="panel-body">
            <?php echo Html::beginForm('','post',['id'=>'post_form']);?>
                <fieldset>
                    <label for="name" class="col-sm-2 control-label">Name</label>
                    <div class="form-group">

                        <input class="form-control" placeholder="" type="text" name="name" value="">
                    </div>
                    <label for="slug" class="col-sm-2 control-label">Slug</label>
                    <div class="form-group">
                        <input class="form-control" placeholder="" type="text" name="slug" value="">
                    </div>
                    <label for="description" class="col-sm-2 control-label">Description</label>
                    <div class="form-group">
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                </fieldset>
                <div class="form-group">
                    <button class="btn btn-primary" id="add_category_button">Add</button>
                </div>
            <?php echo Html::endForm();?>
        </div>
    </div>
</div>
<div class="col-md-8">
        <div class="panel-body">
                    <?= GridView::widget([
                                        'dataProvider' => $dataProvider,

                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn',
                                             'options'=>['class'=>'odd gradeX']
                                            ],

                                            ['attribute'=>'name',
                                            'value'=>'terms.name'
                                            ],
                                            ['attribute'=>'slug',
                                            'value'=>'terms.slug'
                                            ],
                                            'description',
                                            'count',
                                        ],

                                        'tableOptions'=>
                                            ['class'=>'table table-striped table-bordered',
                                             'cellpadding'=>0,
                                             'cellspacing'=>0,
                                              'border'=>0
                                            ],
                                        'rowOptions'=>function ($model, $key, $index, $grid)
                                                      {
                                                        $class = ($key%2?'odd':'even').' gradeX';
                                                        return ['data-key'=>$key,
                                                                'class'=>$class];
                                                       },
                                        'filterSelector'=>"#page_search"
                                    ]); ?>
                </div>
</div>


