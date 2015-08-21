<?php

namespace app\models\dataModel;

use Yii;
use yii\helpers\Url;
use app\models\Options;


/**
 * 主题管理Model
 */
class ThemeData extends BaseModel
{

    public $author;
    public $author_url;
    public $name;
    public function rules()
    {
        return [
            [['author','author_url'],'required','on'=>'set'],
            [['author_url','author_url','name'],'string'],
        ];
    }
    public function scenarios(){
        return [
            'set'=>['author','author_url'],
            'default'=>['author','author_url','name'],
        ];
    }

    /**
     * get all theme from dir themes
     * @return array 主题信息数组
     */
    public function get()
    {
        $dir = Yii::$app->basePath.'/themes/';
        $themes = [];
        if ($handle = opendir($dir))
        {
            while (false !== ($dirname = readdir($handle)))
            {
                if ($dirname != "." && $dirname != "..")
                {
                   $theme = ThemeData::getThemeInfo($dir.$dirname);
                   if( !$theme )
                   {
                    continue;
                   }
                   $themes[] = $theme;
                }
            }
        }
        return $themes;
    }

    /**
     * 从主题文件夹中读取配置信息
     * @param  string $path 主题文件夹路径
     * @return array   主题信息数组
     */
    public function getThemeInfo($path)
    {
        $info = [];
        if( is_file($path.'/package.json') )
        {
            $info = file_get_contents($path.'/package.json');
            $info = json_decode($info,true);
        }
        if($info && is_file($path.'/screenshot.png'))
        {
            $info['screenshot_img'] =  str_replace(Yii::$app->basePath, '', $path).'/screenshot.png';
        }

        return $info;
    }

    /**
     * 切换主题
     * @return boolean 是否成功
     */
    public function select()
    {
        $option = Options::findOne(['option_name'=>'template']);
        if(!$option)
        {
            $option = new Options();
            $option->option_name = 'template';

        }
        $option->option_value = $this->name;
        $res = $option->save();
        if($res)
        {
            $option = Options::findOne(['option_name'=>'theme_mods_'.$this->name]);
            if(!$option)
            {
                $option = new Options();
                $option->option_name = 'theme_mods_'.$this->name;
                $option->option_value = serialize(null);

            }
            $data['nav_menu_locations']['primary'] = MenuData::getMenuInfo()->term_taxonomy_id;
            $option->option_value = serialize($data);

            $option->save();

        }
        return $res;
    }

    /**
     * 获取当前主题名称
     * @return string 主题名称
     */
    public function getCurrentTheme()
    {
        $option = Options::findOne(['option_name'=>'template']);
        if($option)
        {
            return $option->option_value;
        }
        return '';
    }
}
