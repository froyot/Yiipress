<?php

namespace app\models\dataModel;

use Yii;
use yii\helpers\Url;
use app\models\Options;

/**
 * 阅读设置
 */
class ReadingSetting extends BaseModel
{

    public $show_on_front;//首页显示类型page or posts
    public $page_on_front;
    public $posts_per_page = 10;//每页显示数据

    public function rules()
    {
        return [
            [['show_on_front','posts_per_page'],'required','on'=>'set'],
            [['page_on_front','posts_per_page'],'number'],
        ];
    }
    public function scenarios(){
        return [
            'set'=>['show_on_front','posts_per_page','page_on_front'],
            'default'=>['show_on_front','posts_per_page','page_on_front'],
        ];
    }

    /**
     * 保存设置
     */
    public function save()
    {
        //保存设置
        $data = $this->toArray();
        foreach ($data as $key => $item)
        {
            $option = Options::findOne(['option_name'=>$key]);
            if(!$option)
            {
                $option = new Options();
                $option->option_name = $key;
                $option->autoload = 'yes';
            }
            $option->option_value = $item;
            $option->save();
        }
        return true;
    }

    public function get()
    {
        //获取设置
        $data = $this->toArray();

        foreach ($data as $key => $item)
        {
            $option = Options::findOne(['option_name'=>$key]);
            if($option)
            {
                $data[$key] = $option->option_value;
            }
        }
        return $data;
    }

}
