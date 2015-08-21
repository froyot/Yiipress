<?php
use yii\helpers\Html;
?>
<div class="row">
  <div class="col-md-6">
      <div class="content-box-large">
          <div class="panel-heading">
              <div class="panel-title">General Setting</div>
          </div>
          <div class="panel-body">
                <?php echo Html::beginForm('','post',['class'=>'form-horizontal']);?>

                    <div class="form-group">
                      <label class="col-md-2 control-label">Show Index</label>
                      <div class="col-md-10">
                        <div class="radio">
                          <label>
                            <input type="radio" value="posts" name="show_on_front" <?php if($data['show_on_front'] == 'posts'):?>checked="checked"<?php endif;?>>
                            News Posts </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" value="Y-m-d" name="show_on_front" <?php if($data['show_on_front'] == 'page'):?>checked="checked"<?php endif;?>>
                            A Page </label>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="select-1">Page</label>
                          <div class="col-md-6">
                            <select class="form-control" id="select-1">
                              <option>Amsterdam</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Num Per Page</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="posts_per_page" placeholder="Admin Email" name="posts_per_page" value="<?=intval($data['posts_per_page']);?>">
                      </div>
                    </div>

                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </div>
              <?php echo Html::endForm();?>
          </div>
      </div>
  </div>
</div>
