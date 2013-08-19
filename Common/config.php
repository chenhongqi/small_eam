<?php

return array(
    'APP_DEBUG' => true, // 开启调试模式

    'URL_MODEL' => 0,

    'DB_TYPE' => 'mysql', // 数据库类型

    'DB_HOST' => '192.168.8.120', // 数据库服务器地址
    //'DB_NAME' => 'shangman1', // 数据库名称
    'DB_NAME' => 'cps_hongqi', // 数据库名称
    'DB_USER' => 'chenhongqi', // 数据库用户名
    'DB_PWD' => '88888888', // 数据库密码
    'DB_PORT' => '3306', // 数据库端口
    'DB_PREFIX' => '', // 数据表前缀

    //大学生数据采集的订单状态
    'UNISTU_ORDER_STATUS_BEFORE_AUDITING'=>'beforeauditing',//审核前，客户可以修改，【给一个时间段】.
    'UNISTU_ORDER_STATUS_AUDITING'=>'auditing',          //此状态时，客户不能修改.
    'UNISTU_ORDER_STATUS_PROCESSING'=>'processing',      //此状态时，客户不能修改.
    'UNISTU_ORDER_STATUS_FINISHED'=>'finished',          //此状态时，客户不能修改.

    'UNISTU_ORDER_DETAIL_STATUS_AUDIT_FAILED'=>-1,//修改前（审核失败）
    'UNISTU_ORDER_DETAIL_STATUS_AUDIT_DEFAULT'=>0,//修改后
    'UNISTU_ORDER_DETAIL_STATUS_AUDITING'=>1,     //审核中
    'UNISTU_ORDER_DETAIL_STATUS_AUDIT_SUCCESS'=>2,//审核通过
    'UNISTU_ORDER_DETAIL_STATUS_MAKING'=>3,       //制作中
    'UNISTU_ORDER_DETAIL_STATUS_FINISHED'=>4,     //制作完成

    //用户的状态
    'USER_STATUS_REGISTER' => 0,//注册未激活
    'USER_STATUS_ACTIVED'=>1,//注册激活
    'USER_STATUS_LOCKED'=>4,//账号锁定

    //--------------------------------------------------------------
    //图片服务器在web服务器上挂载的位置
    //'MOUNT_DIR'=>'/mnt/webimages/img', //正式环境
    'MOUNT_DIR'=>'.',//开发环境

    //--------------------------------------------------------------
    'TMPL_ACTION_ERROR'     => './Public/hint.html', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => './Public/hint.html', // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE'   => './Public/exception.html',// 异常页面的模板文件


    'BASE_URL' => 'http://127.0.0.1:999', //
    'VAR_FILTERS' => 'htmlspecialchars',
    'SITE_NAME' => "中国图品在线",
    'AUTH_KEY' => 'chinaphotoonline',

    'APP_AUTOLOAD_PATH' => 'App_Shares.Action,App_Shares.Model,App_Shares.Logic', //导入的公共类库位置
    'SHOW_PAGE_TRACE'=>1,

    //每个app对应的入口文件
    'App_Home' => 'index.php',
    'App_Unistu' => 'unistu.php',
    'App_QQZX' => 'collect.php',

    //栏目编码,与数据库中的编码要一致
    'CHANNEL' => array(
        'QQZX' => array(
            'DEFAULT' => 'qqzx',
            'JDHG' => 'jdhg'),
        'TSHC' => array(
            'DEFAULT' => 'home',
            'YBS' => 'ybs',
            'GJSB' => 'gjsb',
            'XHCBS' => 'xhcbs',
            'ZJCBS' => 'zjcbs',
            'XBGY' => 'xbgy'),
        'XRSY' => array(
            'DEFAULT' => 'qqzx',
            'JDHG' => 'jdhg'),
    ),


);




