<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Options;


class BasicController extends Controller
{

    function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);



        $this->getView()->theme = Yii::createObject([
            'class' => '\yii\base\Theme',
            'baseUrl' => '@webroot/themes/basic/web',
            'basePath' => '@app/themes/basic',
        ]);
        Yii::$app->layoutPath = '@app/themes/basic/layouts';
        Yii::$app->params['themPath'] = '@app/themes/basic';
        Yii::$app->params['themName'] = 'basic';
        Yii::setAlias('@themeBase',$this->getView()->theme->basePath);
        Yii::setAlias('@themeWeb','@web/themes/basic/web/');
        Yii::$app->params['SITE_OPTION'] = Options::getAutoLoadOptions();
        //判断模板
        Options::checkTemplate();
        return true;
    }

}
