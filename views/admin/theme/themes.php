<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
$this->registerCssFile('@web/web/css/dialog.css');
?>
<script type="text/javascript">
var _url = "<?=Url::to(['admin/ajax/select-theme']);?>";
</script>
<div class="themelists">
<?php foreach($themes as $theme):?>
<div class="col-md-3">
    <div class="content-box-large">
        <div class="panel-body" style="text-align:center;" data-name="<?=$theme['name'];?>"
            data-version="<?=$theme['version'];?>" data-author="<?=$theme['author'];?>" data-authorurl="<?=$theme['author_url'];?>" data-desc="<?=$theme['description'];?>"
            >
            <a href="javascript:void(0);" class="showDetail" rel="rs-dialog" data-target="myModal">
            <img src="<?=$theme['screenshot_img'];?>" style="max-width:90%;"/>
            </a>
        </div>
        <div class="panel-footer">
            <div class="themeName <?php if($current == $theme['name']):?>current<?php endif;?>"><?=$theme['name'];?><?php if($current != $theme['name']):?><button class="selectTheme" data-name="<?=$theme['name'];?>" style="float:right;">选择</button><?php endif;?></div>
        </div>
    </div>
</div>
<?php endforeach;?>
</div>

<div class="rs-dialog" id="myModal">
    <div class="rs-dialog-box">
        <a class="close" href="#">&times;</a>
        <div class="rs-dialog-header">
            <h3 class="title">标题</h3>
        </div>
        <div class="rs-dialog-body">
            <p>内容</p>
        </div>
        <div class="rs-dialog-footer">
            <input type="button" class="close" value="Close" style="float:right">
        </div>
    </div>
</div>
<div class="rs-overlay" ></div>
