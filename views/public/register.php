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
    <title>Bootstrap Admin Theme v3</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
        <style type="text/css">
    	.mytips{
    		text-align: center;
    		width: 100%;
    		padding: 1em;

  }

    </style>
     <?php $this->head() ?>
  </head>
  <body class="login-bg">
        <?php $this->beginBody() ?>
    <?= Html::csrfMetaTags() ?>
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
		<?php if( Yii::$app->session->hasFlash( 'tips' ) ):?>
		<div class="mytips">
			<?php echo Yii::$app->session->getFlash( 'tips' );?>
		</div>
		<?php endif;?>
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-wrapper">
					<?php echo Html::beginForm(); ?>
			        <div class="box">
			            <div class="content-wrap">
			                <h6>Sign Up</h6>
			                <input class="form-control" type="text" placeholder="E-mail address" name="user_login">
			                <input class="form-control" type="password" placeholder="Password" name="password">
			                <input class="form-control" type="password" placeholder="Confirm Password" name="repassword">
			                <div class="action">
			                    <input class="btn btn-primary signup" value="Sign Up" type="submit"/>
			                </div>
			            </div>
			        </div>

			        <div class="already">
			            <p>Have an account already?</p>
			            <a href="<?php echo Url::to(['public/login']);?>">Login</a>
			        </div>
			        <?php echo Html::endForm(); ?>
			    </div>
			</div>
		</div>
	</div>


    <?php $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>
