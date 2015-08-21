<?php
namespace app\controllers\admin;

use Yii;
use app\models\TermTaxonomy;
use app\models\dataModel\ThemeData;
use app\models\dataModel\MenuData;
use app\component\Qiniu;
/**
 * 文章管理
 */
class AjaxController extends BaseController
{
    public $enableCsrfValidation = false;

    function __construct($id, $module, $config = [])
    {
        \Yii::$app->request->parsers = [
                  'application/json' => 'yii\web\JsonParser',
                  'text/json' => 'yii\web\JsonParser',
                  ];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;//设置返回json对象
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
            Yii::$app->response->data = [
                                        'status'=>false,
                                        'message'=>$name.' param not allow empty'
                                        ];
            \Yii::$app->end(6, \Yii::$app->response);
        }
        return $data;
    }

    /**
     * ajax添加文章分类
     * @return 返回添加后的分类数组对象
     */
    public function actionAddCategory()
    {
        $name = $this->post('name');
        $pid = $this->post('pid');

        $res = TermTaxonomy::addCategory($name,$pid);
        $data = ['status'=>false];
        if ( $res )
        {
            $data['status'] = true;
            $categoryModel = new TermTaxonomy();
            $categorys = $categoryModel->getCategorysByTree();
            $data['data'] = $categorys ;
        }
        else
        {
            $data['message'] = 'add category error';
        }
        return $data;
    }

    /**
     * 切换主题
     * @param  string $name 主题名称
     */
    public function actionSelectTheme($name)
    {
        $theme = new ThemeData();
        $theme->name = $name;
        $res = $theme->select();
        $data['status'] = $res;
        return $data;
    }

    /**
     * 更新菜单
     */
    public function actionEditMenu()
    {
        $type = Yii::$app->request->post('type');
        $menuData = new MenuData();
        $menuData->setScenario('edit-'.$type);
        $res = $menuData->load(Yii::$app->request->post(),'');
        $data['status'] = false;
        if( $res && $menuData->update() )
        {
            $data['status'] = true;
        }
        else
        {
            $data['error'] = $menuData->errorStr;
        }
        return $data;
    }

    public function actionUeditor()
    {
        $path = Yii::getAlias('@webroot').'/web/lib/ueditor/php/config.json';
        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($path)), true);
        $action = Yii::$app->request->get('action');

        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
            break;

            /* 上传图片 */
            case 'uploadimage':
                $file = $_FILES['upfile']['tmp_name'];
                $qiniu = new Qiniu();
                $res = $qiniu->uploade($file);
                $result = NULL;
                if($res)
                {
                    $result = json_encode([
                        'state'=>'SUCCESS',
                        "url" => $res,
                        "title" => $_FILES['upfile']['name'],
                        "original" => $_FILES['upfile']['name'],
                        "type" => '.jpg',
                        "size" => $_FILES['upfile']['size']
                    ]);
                }
                break;

            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
            break;
        }

        /* 输出结果 */
        if (Yii::$app->request->get("callback")) {
            if (preg_match("/^[\w_]+$/", Yii::$app->request->get("callback"))) {
                echo htmlspecialchars(Yii::$app->request->get("callback")) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }


}
