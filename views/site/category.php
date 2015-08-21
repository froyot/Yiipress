<?php
/* @var $this yii\web\View */
$this->title = $category->name.'所有文章';
?>
<div class="site-index" ng-controller="IndexCtrl">
 <ul class="col-md-9 list-group">
        <li class="list-group-item" ng-repeat="artical in articals">
            <a href="{{artical.url}}" target="_blank">
                <h4 class="list-group-item-heading">
                    {{artical.title}}
                </h4>
            </a>
            <p class="list-group-item-text">
                {{artical.description}}
            </p>
        </li>
    </ul>
    <button class="btn col-md-9" ng-show="!noData" ng-click="loadMore()">加载更多</button>
    
</div>
<script type="text/javascript">
var _category = "<?php echo $category->id;?>";
</script>
