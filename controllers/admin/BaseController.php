<?php
namespace app\controllers\admin;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;

class BaseController extends Controller
{
    public $layout = "admin";
    public $otherParams;
    function __construct($id, $module, $config = [])
    {
        $this->otherParams = isset(Yii::$app->request->queryParams['otherParams'])?
                        Yii::$app->request->queryParams['otherParams']:array();
        if( Yii::$app->request->get('keywords') )
        {
            $this->otherParams['keywords'] = Yii::$app->request->get('keywords');
        }

        return parent::__construct($id, $module, $config);
    }


    /**
     * post获取参数自动检测参数是否空
     * @param  string  $name       参数名称
     * @param  boolean $allowEmpty 是否允许不传
     */
    protected function post($name,$allowEmpty=false)
    {
        $data = Yii::$app->request->post($name);
        if(!$allowEmpty && $data=== null)
        {
            return '';
        }
        return $data;
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
