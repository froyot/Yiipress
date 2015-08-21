<?php
$admin_menu = require(__DIR__ . '/admin_menu.php');
return array_merge([
    'adminEmail' => 'admin@example.com',

    //默认模板名称
    'DEFAULT_TEMPLATE'=>'yiipress_basic',

    //缓存键值设置
    'OPTION_CACHE'=>'_SITE_OPTIONS',
    'MENU_CACHE'=>'_SITE_MENU',

    'QINIU_DOMAIN'=>'http://7xkcoe.com1.z0.glb.clouddn.com',
    'QINIU_ACCESS_KEY'=>'ipdCs-vTbD29bntEjiJADrBGJnTEhDP4phofxZZz',
    'QINIU_SECRET_KEY'=>'61Bj-ZVjlKUwRdHx7zfAzuBRLgLtmqf5MWaNm62d',
    'QINIU_BUCKET'=>'froyoimg',

],
$admin_menu
);
