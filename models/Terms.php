<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%terms}}".
 *
 * @property string $term_id
 * @property string $name
 * @property string $slug
 * @property string $term_group
 */
class Terms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%terms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['term_id', 'term_group'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'term_id' => 'Term ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'term_group' => 'Term Group',
        ];
    }
}
