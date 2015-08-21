<?php

namespace app\models\dataModel;

use Yii;
use yii\helpers\Url;
use app\models\Options;

/**
 * CMS系统常规设置ORM操作模型
 */
class GeneralSetting extends BaseModel
{

    public $blogname;
    public $blogdescription;
    public $admin_email;
    public $date_format;
    public $zh_cn_l10n_icp_num;

    public function rules()
    {
        return [
            [['blogname','blogdescription','admin_email','date_format'],'required','on'=>'set'],
            [['zh_cn_l10n_icp_num','blogname','blogdescription','admin_email','date_format'],'string'],
            ['date_format','in','range'=>['Y-m-d','Y/m/d','m/d/Y','d/m/Y','Y年m月d日']],
        ];
    }
    public function scenarios(){
        return [
            'set'=>['blogname','blogdescription','admin_email','date_format','zh_cn_l10n_icp_num'],
            'default'=>['blogname','blogdescription','admin_email','date_format','zh_cn_l10n_icp_num'],
        ];
    }

    /**
     * 保存常规设置
     * @return boolean 是否成功
     */
    public function save()
    {
        //保存常规设置
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
            $res = $option->save();//option save之后会更新缓存
            if(!$res)
            {
                Yii::error($option->errors);
            }
        }
        return true;
    }

    /**
     * 获取设置数组
     * @return array 设置数组
     */
    public function get()
    {
        //获取常规设置
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
