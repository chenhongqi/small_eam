<?php
   
//phpinfo();

//开启调试模式
define('APP_DEBUG',TRUE);
//phunit model测试开关
define("APP_PHPUNIT", false);

define('WEB_ROOT','./');
#define( 'CMS_ROOT', dirname( __FILE__ ).'/' );
//HTML路径
#define('HTML_PATH','./Public/statics/Html/');
//ThinkPHP路径
define('THINK_PATH','./ThinkPHP/');
//定义项目名称和路径
define('APP_NAME', 'App_Home');
define('APP_PATH', './App_Home/');//./表示当前目录层
// 加载框架入口文件
require( THINK_PATH.'ThinkPHP.php');
