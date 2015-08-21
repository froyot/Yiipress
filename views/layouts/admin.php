<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use app\assets\AdminAsset;

AdminAsset::register($this);
$menuId = Yii::$app->requestedRoute;
$menus = Yii::$app->params['adminMenu'];

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>

    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="header">
         <div class="container">
            <div class="row">
               <div class="col-md-5">
                  <!-- Logo -->
                  <div class="logo">
                     <h1><a href="<?php echo Url::to(['admin/site/index']);?>">cms后台</a></h1>
                  </div>
               </div>
               <div class="col-md-5">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="input-group form">
                           <input type="text" class="form-control" name="keywords" placeholder="Search..." id="page_search" value="<?php echo Yii::$app->request->get('keywords');?>">
                           <span class="input-group-btn">
                             <button class="btn btn-primary search-button" type="button">Search</button>
                           </span>
                      </div>
                    </div>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="navbar navbar-inverse" role="banner">
                      <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                        <ul class="nav navbar-nav">
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo Yii::$app->user->identity->display_name;?><b class="caret"></b></a>
                            <ul class="dropdown-menu animated fadeInUp">
                              <li><a href="<?php echo Url::to(['admin/user/index']);?>">Profile</a></li>
                              <li><?php echo Html::a('Logout',Url::to(['public/logout']));?>
                            </ul>
                          </li>
                        </ul>
                      </nav>
                  </div>
               </div>
            </div>
         </div>
    </div>
    <div class="page-content">
       <div class="row">
          <div class="col-md-2">
            <div class="sidebar content-box" style="display: block;">
                <ul class="nav">
                    <!-- Main menu -->
                    <li class=" "><a href="<?=Url::to(['admin/site/index']);?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                    <!--
                    <li><a href="calendar.html"><i class="glyphicon glyphicon-calendar"></i> Calendar</a></li>
                    <li><a href="stats.html"><i class="glyphicon glyphicon-stats"></i> Statistics (Charts)</a></li>
                    <li><a href="tables.html"><i class="glyphicon glyphicon-list"></i> Tables</a></li>
                    <li><a href="buttons.html"><i class="glyphicon glyphicon-record"></i> Buttons</a></li>
                    <li><a href="editors.html"><i class="glyphicon glyphicon-pencil"></i> Editors</a></li>
                    <li><a href="forms.html"><i class="glyphicon glyphicon-tasks"></i> Forms</a></li>
                -->
                    <li class="submenu <?php if( in_array($menuId, $menus['posts'])):?>current open<?php endif;?>" >
                         <a href="#">
                            <i class="glyphicon glyphicon-pencil"></i> Posts
                            <span class="caret pull-right"></span>
                         </a>
                         <!-- Sub menu -->
                         <ul>
                            <li>
                              <a href="<?php echo Url::to(['admin/posts/index']);?>"
                              class="<?php if( $menuId == 'admin/posts/index'):?>current<?php endif;?>"
                              >All Posts</a>
                            </li>
                            <li>
                              <a href="<?php echo Url::to(['admin/posts/add']);?>"
                                class="<?php if( $menuId == 'admin/posts/add'):?>current<?php endif;?>"
                              >Add New</a>
                            </li>
                            <li>
                              <a href="<?php echo Url::to(['admin/category/index']);?>"
                              class="<?php if( $menuId == 'admin/category/index'):?>current<?php endif;?>"
                              >Categorys</a>
                            </li>
                            <li>
                              <a href="<?php echo Url::to(['admin/tags/index']);?>"
                               class="<?php if( $menuId == 'admin/tags/index'):?>current<?php endif;?>"
                              >Tags</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu <?php if( in_array($menuId, Yii::$app->params['adminMenu']['page'])):?>current open<?php endif;?>" >
                         <a href="#">
                            <i class="glyphicon glyphicon-list"></i> Pages
                            <span class="caret pull-right"></span>
                         </a>
                         <!-- Sub menu -->
                         <ul>
                            <li>
                              <a href="<?php echo Url::to(['admin/page/index']);?>"
                               class="<?php if( $menuId == 'admin/page/index'):?>current<?php endif;?>"
                              >All Pages</a>
                            </li>
                            <li>
                              <a href="<?php echo Url::to(['admin/page/add']);?>"
                               class="<?php if( $menuId == 'admin/page/add'):?>current<?php endif;?>"
                              >Add</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu <?php if( in_array($menuId, Yii::$app->params['adminMenu']['setting'])):?>current open<?php endif;?>" >
                         <a href="#">
                            <i class="glyphicon glyphicon-list"></i> Setting
                            <span class="caret pull-right"></span>
                         </a>
                         <!-- Sub menu -->
                         <ul>
                            <li>
                              <a href="<?php echo Url::to(['admin/setting/general']);?>"
                               class="<?php if( $menuId == 'admin/setting/general'):?>current<?php endif;?>"
                              >General</a>
                            </li>
                            <li>
                              <a href="<?php echo Url::to(['admin/setting/reading']);?>"
                               class="<?php if( $menuId == 'admin/setting/reading'):?>current<?php endif;?>"
                              >Reading</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu <?php if( in_array($menuId, Yii::$app->params['adminMenu']['theme'])):?>current open<?php endif;?>">
                         <a href="#">
                            <i class="glyphicon glyphicon-list"></i> Views
                            <span class="caret pull-right"></span>
                         </a>
                         <!-- Sub menu -->
                         <ul>
                            <li>
                              <a href="<?php echo Url::to(['admin/theme/themes']);?>"
                               class="<?php if( $menuId == 'admin/theme/themes'):?>current<?php endif;?>"
                              >Themes</a>
                            </li>
                            <li>
                              <a href="<?php echo Url::to(['admin/theme/menu']);?>"
                               class="<?php if( $menuId == 'admin/theme/menu'):?>current<?php endif;?>"
                              >Menu</a>
                            </li>

                        </ul>
                    </li>

                </ul>
             </div>
          </div>
          <div class="col-md-10">
    <?php if( Yii::$app->session->hasFlash( 'tips' ) ):?>
    <div class="mytips">
      <?php echo Yii::$app->session->getFlash( 'tips' );?>
    </div>
    <?php endif;?>
        <?= $content ?>
                  </div>
        </div>

    </div>

    <footer>
         <div class="container">

            <div class="copy text-center">
               Copyright 2014 <a href='#'>Website</a>
            </div>

         </div>
      </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
