<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%options}}".
 *
 * @property string $option_id
 * @property string $option_name
 * @property string $option_value
 * @property string $autoload
 */
class Options extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%options}}';
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $res = parent::save($runValidation, $attributeNames);
        Yii::$app->cache->set(Yii::$app->params['OPTION_CACHE'],null);
        return $res;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_value'], 'required'],
            [['option_id'], 'integer'],
            [['option_value'], 'string'],
            [['option_name'], 'string', 'max' => 64],
            [['autoload'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'option_id' => 'Option ID',
            'option_name' => 'Option Name',
            'option_value' => 'Option Value',
            'autoload' => 'Autoload',
        ];
    }

    /**
     * 获取网站自动载入配置信息，并写入缓存
     * @return array 配置数组
     */
    public function getAutoLoadOptions()
    {
        $optionArray = unserialize(
                Yii::$app->cache->get(Yii::$app->params['OPTION_CACHE'])
            );
        if($optionArray)
            return $optionArray;
        $options = Options::find()->where(['autoload'=>'yes'])->all();
        $optionArray = [];
        foreach ($options as $key => $option)
        {
            $optionArray[$option->option_name] = $option->option_value;
        }
        Yii::$app->cache->set(
                Yii::$app->params['OPTION_CACHE'],serialize($optionArray)
            );
        return $optionArray;
    }

    /**
     * 检查是否设置模板，没有设置，则设置默认模板
     * @return NULL
     */
    public function checkTemplate()
    {
        $option = Options::findOne(['option_name'=>'template']);
        if(!$option)
        {
            $option = new Options();
            $option->option_name = 'template';
        }
        if(!$option->option_value)
        {
            $option->option_value = Yii::$app->params['DEFAULT_TEMPLATE'];
            $option->save();
            Yii::$app->params['SITE_OPTION']['template'] = $option->option_value;
        }
    }

}
