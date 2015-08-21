<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AdminAsset;
AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
  </head>
  <body class="login-bg">
  	<?php $this->beginBody() ?>
  	<div class="header">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-12">
	              <!-- Logo -->
	              <div class="logo">
	                 <h1><a href="<?php echo Yii::$app->homeUrl;?>">CMS</a></h1>
	              </div>
	           </div>
	        </div>
	     </div>
	</div>

	<div class="page-content container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-wrapper">
			        <div class="box">
			            <div class="content-wrap">
			                <h6>Reset Password</h6>
			                <input class="form-control" type="text" placeholder="E-mail address">
			                <div class="action">
			                    <a class="btn btn-primary signup" href="index.html">Reset Password</a>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>


  </body>
  <?php $this->endBody() ?>
</html>

<?php $this->endPage() ?>
