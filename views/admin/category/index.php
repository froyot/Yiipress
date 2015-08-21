<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
?>
<div id="page_add_category">
<div class="col-md-4">
    <div class="content-box-large">
        <div class="panel-heading">
            <div class="panel-title">Add New Category</div>
        </div>
        <div class="panel-body">
            <?php echo Html::beginForm('','post',['id'=>'post_form']);?>
                <fieldset>
                    <label for="name" class="col-sm-2 control-label">Name</label>
                    <div class="form-group">
                        <?php echo Html::tag('input',
                                                '',
                                                [
                                                'type'=>'text',
                                                'name'=>'name',
                                                'value'=>$category['name'],
                                                'class'=>'form-control'
                                                ]);
                        ?>
                    </div>
                    <label for="slug" class="col-sm-2 control-label">Slug</label>
                    <div class="form-group">
                        <?php echo Html::tag('input',
                                                '',
                                                [
                                                'type'=>'text',
                                                'name'=>'slug',
                                                'value'=>$category['slug'],
                                                'class'=>'form-control'
                                                ]);
                        ?>
                    </div>
                    <label for="parent" class="col-sm-2 control-label">Parent</label>
                    <div class="form-group">
                        <select name="parent" class="form-control">
                            <option value="0">无</option>
                            <?php foreach($categorys as $cat):?>
                                <?php echo Html::tag('option',$cat['name'],
                                                    [
                                                    'value'=>$cat['term_taxonomy_id'],
                                                    'selected'=>$category['parent'] == $cat['term_taxonomy_id']?true:false
                                                    ]);?>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <label for="description" class="col-sm-2 control-label">Description</label>
                    <div class="form-group">
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                </fieldset>
            <?php echo Html::endForm();?>
                <div class="form-group">
                    <button class="btn btn-primary" id="add_category_button">Add</button>
                </div>
        </div>
    </div>
</div>
<div class="col-md-8">
        <div class="panel-body">
                    <?= GridView::widget([
                                        'dataProvider' => $dataProvider,
                                        // 'filterModel' => $searchModel,
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
</div>


