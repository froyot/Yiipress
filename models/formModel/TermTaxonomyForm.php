<?php

namespace app\models\formModel;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TermTaxonomy;
use app\models\Terms;
/**
 * TermTaxonomyForm represents the model behind the search form about `app\models\TermTaxonomy`.
 */
class TermTaxonomyForm extends TermTaxonomy
{
    public $name;
    public $slug;
    public function attributes()
    {
        // add related fields to searchable attributes
      return array_merge(parent::attributes(), ['name','slug']);

    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['term_taxonomy_id', 'term_id', 'parent', 'count'], 'integer'],
            [['taxonomy', 'description'], 'safe'],
            [['name','slug','description'],'string'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $otherParam = array())
    {

        $query = TermTaxonomy::find()->joinWith('terms');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $dataProvider->sort->attributes['name'] = [
            'asc' => [Terms::tableName().'.name' => SORT_ASC],
            'desc' => [Terms::tableName().'.name' => SORT_DESC],
            ];
        $dataProvider->sort->attributes['slug'] = [
            'asc' => [Terms::tableName().'.slug' => SORT_ASC],
            'desc' => [Terms::tableName().'.slug' => SORT_DESC],
            ];

        $query->andFilterWhere([
            'term_taxonomy_id' => $this->term_taxonomy_id,
            'term_id' => $this->term_id,
            'parent' => $this->parent,
            'count' => $this->count,
        ]);

        $query->andFilterWhere(['like', 'taxonomy', $this->taxonomy])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', Terms::tableName().'.name', $this->name])
            ->andFilterWhere(['like', Terms::tableName().'.slug', $this->slug]);
        if( isset($otherParam['taxonomy']) )
        {
            $query->andFilterWhere(['taxonomy'=>$otherParam['taxonomy']]);
        }
        if( isset($otherParam['keywords']) )
        {
            $query->andWhere(
                Terms::tableName().'.name like :keywords or '.
                Terms::tableName().'.slug like :keywords or description like :keywords',
                [':keywords'=>'%'.$otherParam['keywords'].'%']
                );

        }
        return $dataProvider;
    }
}
