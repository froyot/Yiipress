<?php
use yii\helpers\Html;
?>

<!-- Page Header -->
<!-- Set your background image for this header on the line below. -->
<header class="intro-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="site-heading">
                    <h2><?= Html::encode($data->title);?></h2>
                    <hr class="small">
                    <span class="subheading">Postby <?=$data->author;?> on <?=$data->date;?></span>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="container">
<?php
echo $content;
?>
</div>
