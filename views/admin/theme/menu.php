<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
?>
<script type="text/javascript">
var _edit_url = "<?=Url::to(['admin/ajax/edit-menu']);?>";
</script>
<div class="col-md-4">
    <div class="content-box-large">
        <div class="panel-heading">
            <div class="panel-title">Add New Menu</div>
        </div>
        <div class="panel-body">
            <?php echo Html::beginForm('','post',['id'=>'post_form']);?>
                <fieldset>
                    <label for="name" class="col-sm-2 control-label">Name</label>
                    <div class="form-group">
                        <select  class="form-control" name="type" id="select_type">
                            <option value="category">Category</option>
                            <option value="page">Page</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="form-group inputholder" id="category_selector">
                        <select  class="form-control" name="cat_key_id">
                            <option value="0">Select Category</option>
                            <?php foreach ($categorys as $key => $item):?>
                            <option value="<?php echo $item['term_taxonomy_id'];?>">
                                <?php echo $item['name'];?>
                            </option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group inputholder" id="page_selector" style="display:none;">
                        <select  class="form-control" name="page_key_id">
                        <?php foreach($pages as $page):?>
                            <option value="0">Select Page</option>
                            <option value="<?=$page->ID;?>">
                                <?=$page->post_title;?>
                            </option>
                        <?php endforeach;?>
                        </select>
                    </div>
                    <div id="coustorm_selector" class="inputholder" style="display:none;">
                    <label for="title" class="col-sm-2 control-label">Title</label>
                    <div class="form-group">
                        <input class="form-control" placeholder="" type="text" name="title" value="">
                    </div>
                    <label for="url" class="col-sm-2 control-label">URL</label>
                    <div class="form-group">
                        <input class="form-control" placeholder="" type="text" name="url" value="">
                    </div>
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
        <table id="user" class="table table-bordered table-striped menu-table" style="clear: both">
            <thead>
                <tr>
                    <th width="10%">Index</th>
                    <th width="30%">Title</th>
                    <th width="10%">Type</th>
                    <th width="20%">Value</th>
                    <th width="30%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($menus as $key=>$menu):?>
                <tr data-post="<?=$menu['post_id'];?>">
                    <td width="10%"><?=($key+1);?></td>
                    <td width="30%">
                        <?php if($menu['type'] == 'custom'):?>
                            <input name="title" value="<?=$menu['title'];?>" class="inputchange" data-value="<?=$menu['title'];?>"/>
                        <?php else:?>
                            <?=$menu['title'];?>
                        <?php endif;?>
                    </td>
                    <td width="10%"><?=$menu['type'];?></td>
                    <td width="20%">
                    <?php if($menu['type'] == 'custom'):?>
                        <input name="url" value="<?=$menu['url'];?>" class="inputchange" data-value="<?=$menu['url'];?>"/>
                    <?php elseif($menu['type'] == 'category'):?>
                        <select class="form-control selectchange" name="category" data-post="<?=$menu['post_id'];?>">
                            <option value="0">default Category</option>
                            <?php foreach ($categorys as $key => $item):?>
                            <option value="<?php echo $item['term_taxonomy_id'];?>"
                                <?php if($menu['_key_id'] == $item['term_taxonomy_id']):?> selected="true"<?php endif;?>
                            >
                                <?php echo $item['name'];?>
                            </option>
                            <?php endforeach;?>
                        </select>
                    <?php else:?>
                        <select class="form-control selectchange" name="page" data-post="<?=$menu['post_id'];?>">
                        <?php foreach($pages as $page):?>

                            <option value="<?=$page->ID;?>"
                                <?php if($menu['_key_id'] == $page->ID):?> selected="true"<?php endif;?>
                            >
                                <?=$page->post_title;?>
                            </option>

                        <?php endforeach;?>
                        </select>
                    <?php endif;?>
                    </td>
                    <td width="30%"><a href="<?=Url::to(['admin/theme/delete-menu','id'=>$menu['post_id']]);?>">Delete</a></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>


