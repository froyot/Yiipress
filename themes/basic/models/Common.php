<?php
namespace themeBase\models;
use Yii;
use app\models\Postmeta;
use app\models\TermRelationships;
use app\models\dataModel\MenuData;

class Common{

    function powered()
    {
        $ICP = '';
        if(isset(Yii::$app->params['SITE_OPTION']['zh_cn_l10n_icp_num']))
            $ICP = '<span style="display:block;text-align:center;"><a href="#">'.Yii::$app->params['SITE_OPTION']['zh_cn_l10n_icp_num'].'</a></span>';
        return 'Copyright &copy; YiiPress ' .date('Y').' , Powered by <a href="http://blog.charme.me/" rel="external">Froyo</a>'.$ICP;
    }

    function getMenus()
    {
        $menus = MenuData::getMenus();

        return $menus;
    }
}
