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
                  <label for="blogname" class="col-sm-2 control-label">Blog Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="blogname" placeholder="Blog Name" name="blogname" value="<?=$data['blogname'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Description" class="col-sm-2 control-label">Description</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="Description" placeholder="Description" name="blogdescription" value="<?=$data['blogdescription'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Admin Email</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="email" placeholder="Admin Email" name="admin_email" value="<?=$data['admin_email'];?>">
                  </div>
                </div>

                    <div class="form-group">
                      <label class="col-md-2 control-label">Date Format</label>
                      <div class="col-md-10">
                        <div class="radio">
                          <label>
                            <input type="radio" value="Y年m月d日" name="date_format" <?php if($data['date_format'] == 'Y年m月d日'):?>checked="checked"<?php endif;?>>
                            <?=date('Y年m月d日');?> </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" value="Y-m-d" name="date_format" <?php if($data['date_format'] == 'Y-m-d'):?>checked="checked"<?php endif;?>>
                            <?=date('Y-m-d');?> </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" value="Y/m/d" name="date_format" <?php if($data['date_format'] == 'Y/m/d'):?>checked="checked"<?php endif;?>>
                            <?=date('Y/m/d');?> </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" value="d/m/Y" name="date_format" <?php if($data['date_format'] == 'd/m/Y'):?>checked="checked"<?php endif;?>>
                            <?=date('d/m/Y');?> </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" value="m/d/Y" name="date_format" <?php if($data['date_format'] == 'm/d/Y'):?>checked="checked"<?php endif;?>>
                            <?=date('m/d/Y');?> </label>
                        </div>
                      </div>
                    </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">ICP Number</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="ICPNumber" placeholder="ICPNumber" name="zh_cn_l10n_icp_num" value="<?=$data['zh_cn_l10n_icp_num'];?>">
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
