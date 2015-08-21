<?php
namespace app\models\dataModel;
use Yii;
use yii\base\Model;

/**
 * 数据模型基类，用于直接与用户交互相关数据ORM操作
 */

class BaseModel extends Model
{
    /**
     * 获取数据处理错误字符串
     * @return string errorStr 返回所有错误数组逗号连接成的字符串
     */
    public function getErrorStr()
    {
        $errorStr = '';
        $attrs = $this->attributes();
        $attrs[] = 'tips';
        foreach ($attrs as $key => $attr)
        {
           if ( isset( $this->errors[$attr]) )
           {
                $errorStr .= $this->errors[$attr][0].',';
           }
        }

        if( $errorStr != '' )
        {
            $errorStr = rtrim($errorStr,',');
        }

        return $errorStr;
    }

    /**
     * 模型载入数据
     * @param  string $data 请求数据
     * @param  string $formModel 表单名称，默认空
     * @return boolean           是否成功
     */
    public function load($data, $formModel = '')
    {
        if( !$data )
        {
            $this->addError('tips','params empty');
            return false;
        }

        $res = parent::load($data, $formModel);
        $this->afterLoad($data);
        return $res;
    }

    public function afterLoad($data)
    {

    }
}
