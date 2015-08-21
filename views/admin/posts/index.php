<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\grid\GridView;

$this->registerCss("th .desc:before {
  background: 0 0;
  content: '\f142';
  font: 400 20px/1 dashicons;
  speak: none;
  display: inline-block;
  padding: 0;
  top: -4px;
  left: -8px;
  line-height: 10px;
  position: relative;
  vertical-align: top;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-decoration: none!important;
  color: #444;
}");

$this->registerJsFile("@web/js/admin/main.js?t=111");
?>
		  <div class="col-md-10">
  			<div class="content-box-large" id="list_content">
  				<div class="panel-heading">
					<div class="panel-title">Posts</div><a class="btn btn-primary btn-xs" href="<?php echo Url::to(['admin/posts/add']);?>">Add New</a>
					<?php echo Html::beginForm();?>
					<div class="filter">

						<div class="form-group">
							<div class="col-md-2">

								<select class="form-control" id="action">
									<option>Amsterdam</option>
									<option>Atlanta</option>
									<option>Baltimore</option>
								</select>


							</div>
                            <div class="my-filters">
    							<div class="col-md-2">
    								<select class="form-control" id="date" name="otherParams[date]">
    									<option value="">all</option>
    									<?php foreach ($dates as $key => $item):?>
    									<option value="<?php echo $item['post_date'];?>"
                                            <?php if(
                                                isset($otherParams['date']) &&
                                                $otherParams['date'] == $item['post_date']
                                                ):?> selected="true"
                                            <?php endif;?>
                                        >
    										<?php echo $item['post_date'];?>
    									</option>
    									<?php endforeach;?>
    								</select>
    							</div>
    							<div class="col-md-2">
    								<select class="form-control" id="category" name="otherParams[category]">
    									<option value="0">default Category</option>
    									<?php foreach ($categorys as $key => $item):?>
    									<option value="<?php echo $item['term_taxonomy_id'];?>"
                                            <?php if(
                                                    isset($otherParams['category']) &&
                                                    $otherParams['category'] == $item['term_taxonomy_id']
                                                    ):?> selected="true"
                                            <?php endif;?>
                                        >
    										<?php echo $item['name'];?>
    									</option>
    									<?php endforeach;?>
    								</select>
    							</div>
                                <div class="col-md-4">
                                    <input class="form-control"
                                            name="otherParams[keyword]"
                                            value="<?php echo isset($otherParams['keyword'])?$otherParams['keyword']:'';?>">
                                    </select>
                                </div>
                            </div>

						</div>


					</div>
					<?php echo Html::endForm();?>
				</div>
  				<div class="panel-body">
					<?= GridView::widget([
								        'dataProvider' => $dataProvider,
								        // 'filterModel' => $searchModel,
								        'columns' => [
								            ['class' => 'yii\grid\SerialColumn',
								             'options'=>['class'=>'odd gradeX']
								            ],

								            'ID',
								            'post_title',
								            'post_author',
								            'post_date',
								            'ping_status',

								            [
                            'class' => 'yii\grid\ActionColumn',
                            'buttons'=>[
                                'view'=>function ($url, $model, $key) {
                                    $url = Url::to(['posts/detail','id'=>$model->ID]);
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('yii', 'View'),
                                        'target'=>'_blank',
                                        'data-pjax' => '0',
                                    ]);
                                }
                            ]

                            ],
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

                                        'filterSelector'=>".my-filters input,.my-filters select"
								    ]); ?>
  				</div>
  			</div>
		  </div>

