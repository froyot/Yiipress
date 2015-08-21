<?php
namespace themeBase\models;
use Yii;
use yii\base\Model;

use app\models\Users;

use yii\helpers\Html;

class UsersModel extends Model
{

    public function getInfo($id)
    {
        $user = Users::findOne($id);
        return $user;
    }
}
