<?php
namespace app\component;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Qiniu
{
	public $token;
	public $error;
	public $domain;
	function __construct()
	{
        $this->domain = Yii::$app->params['QINIU_DOMAIN'];
		$accessKey = Yii::$app->params['QINIU_ACCESS_KEY'];
        $secretKey = Yii::$app->params['QINIU_SECRET_KEY'];
        $auth = new Auth($accessKey, $secretKey);
        $bucket = Yii::$app->params['QINIU_BUCKET'];
        $this->token = $auth->uploadToken($bucket);
	}

	public function uploade($fileName)
	{
        if(!is_file($fileName))
        {
        	$this->error = 'file not exist';
        	return null;
        }
		$uploadMgr = new UploadManager();
        $fp = fopen ($fileName, "rb" );
        $filesize=abs(filesize($fileName));
 		$file_data = fread ( $fp, $filesize ) ;
        list($ret, $err) = $uploadMgr->put($this->token, null,  $file_data);
        if ($err !== null) {
    		$this->error = $err;
    		return null;
        } else {
            return $this->domain.'/'.$ret['key'];
        }
	}

}
