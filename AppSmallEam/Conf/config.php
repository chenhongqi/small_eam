<?php
$config_arr1 = include_once WEB_ROOT . 'Common/config.php';

//在这里添加本模块的自己的定义配置文件
$config_arr2 = array(
    'LANG_SWITCH_ON'    => true,        //开启多语言支持开关
    'DEFAULT_LANG'        => 'zh-cn',    // 默认语言
    'LANG_AUTO_DETECT'    => true,    // 自动侦测语言

    'TOKEN_ON' => false, // 是否开启令牌验证
    'TOKEN_NAME' => '__hash__', // 令牌验证的表单隐藏字段名称
    'TOKEN_TYPE' => 'md5', //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET' => FALSE, //令牌验证出错后是否重置令牌 默认为true


    //----配置邮件--------------------------------
    'MAIL_ADDRESS'=>'chenhongqi@sina.com', // 邮箱地址
    'MAIL_SMTP'=>'smtp.sina.com.cn', // 邮箱SMTP服务器
    'MAIL_LOGINNAME'=>'chenhongqi', // 邮箱登录帐号
    'MAIL_PASSWORD'=>'sina@lover1025', // 邮箱密码
    'MAIL_SENDER'=>'chenhongqi', //发件人名字
    //--------------------------------------------

    );

return array_merge($config_arr1, $config_arr2);
