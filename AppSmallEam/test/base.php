<?php

// 定义ThinkPHP框架路径

define('APP_DEBUG',TRUE);
//model测试开关
define("APP_PHPUNIT", true);

define('WEB_ROOT','../../');

define('THINK_PATH', '../../ThinkPHP/');
//定义项目名称和路径
define('APP_NAME', 'App_Home');
define('APP_PATH', '../../App_Home/');

define('DB_NAME','mysql:host=192.168.8.120;dbname=cps_hongqi');
define('DB_USER',"chenhongqi");
define('DB_PWD',"88888888");

// 加载框架公共入口文件
require THINK_PATH."/ThinkPHP.php";

