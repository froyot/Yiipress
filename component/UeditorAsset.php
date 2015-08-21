<?php
namespace app\component;

use yii\web\AssetBundle;

class UeditorAsset extends AssetBundle
{
    public $js = [
        'ueditor.config.js',
        'ueditor.all.js',
    ];
    public $css = [
    ];
    public function init()
    {
        $this->sourcePath =$_SERVER['DOCUMENT_ROOT'].\Yii::getAlias('@myweb').'/lib/ueditor'; //设置资源所处的目录
    }
}
