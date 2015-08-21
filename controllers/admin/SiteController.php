<?php
namespace app\controllers\admin;

use Yii;


class SiteController extends BaseController
{
	public $layout = "admin";
	public function actionIndex()
	{
		return $this->render('index');
	}
}
