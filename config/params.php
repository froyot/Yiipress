<?php
$admin_menu = require(__DIR__ . '/admin_menu.php');
return array_merge([
    'adminEmail' => 'admin@example.com',

    //默认模板名称
    'DEFAULT_TEMPLATE'=>'yiipress_basic',

    //缓存键值设置
    'OPTION_CACHE'=>'_SITE_OPTIONS',
    'MENU_CACHE'=>'_SITE_MENU',

    'QINIU_DOMAIN'=>'',
    'QINIU_ACCESS_KEY'=>'',
    'QINIU_SECRET_KEY'=>'',
    'QINIU_BUCKET'=>'',

],
$admin_menu
);
