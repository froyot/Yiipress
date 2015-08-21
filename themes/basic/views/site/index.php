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
                    <h1><?= Html::encode(Yii::$app->params['SITE_OPTION']['blogname']);?></h1>
                    <hr class="small">
                    <span class="subheading"><?= Html::encode(Yii::$app->params['SITE_OPTION']['blogdescription']);?></span>
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
