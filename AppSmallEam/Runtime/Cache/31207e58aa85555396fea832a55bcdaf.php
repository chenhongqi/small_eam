<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="Robots" content="all">
    <title>中国图品在线</title>

    <link href="__PUBLIC__/css/public.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="../Public/css/style.css" rel="stylesheet" type="text/css" media="screen"/>
    <script language=JavaScript type="text/javascript" src="__PUBLIC__/js/jquery-1.9.0.min.js"/>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery("a.alogin_reg").each(function () {
                var old = jQuery(this).attr("href");
                jQuery(this).attr("href", old + "&forward=/<?php echo ($AppName); ?>/Index/index");
            });
        });
    </script>

</head>

<body>

<?php if(isset($_SESSION['logineduser'])): echo ($_SESSION['logineduser']['uname']); ?> 你好！
    <a href="__ROOT__/index.php?m=User&a=logout">退出</a>
    <a href="<?php echo U('Center/orders');?>">我的订单</a>
    <a href="<?php echo U('Center/personal');?>">用户中心</a>
    <a href="<?php echo U('Index/test');?>">test</a>
    <?php else: ?>
    <div style="color: #f5f5f5">
        <a class="alogin_reg" href="<?php echo U('User/register');?>">注册</a>
        <a class="alogin_reg" href="<?php echo U('User/login');?>">登录</a>
    </div><?php endif; ?>

<div align="center" style="margin-bottom:30px;"><img src="../Public/images/bg.jpg" border="0" usemap="#Map"/>
    <map name="Map" id="Map">
        <area shape="rect" coords="11,107,129,194" href="#"/>
        <!--<area shape="rect" coords="136,107,255,195" href="../collect/index.html" />-->
        <area shape="rect" coords="136,107,255,195" href="__ROOT__/qqzx.php"/>
        <area shape="rect" coords="276,106,520,193" href="__ROOT__/sheyingshi.php"/>
        <area shape="rect" coords="529,106,774,194" href="../dzzz/magazine.html"/>
        <area shape="rect" coords="786,107,1008,292" href="infotab.html"/>
        <area shape="rect" coords="10,201,130,290" href="../xrsy/index.html"/>
        <area shape="rect" coords="137,202,254,290" href="../zttk/index.html"/>
        <area shape="rect" coords="274,200,521,290" href="../yssc/index.html"/>
        <area shape="rect" coords="531,299,648,387" href="../sgaf/index.html"/>
        <area shape="rect" coords="10,300,256,484" href="picmall.html"/>
        <area shape="rect" coords="275,298,523,387" href="../sylx/index.html"/>
        <area shape="rect" coords="785,297,1009,385" href="yaanjiayou.html"/>
        <area shape="rect" coords="276,395,522,484" href="self-media.html"/>
        <area shape="rect" coords="530,395,774,483" href="AD.html"/>
        <area shape="rect" coords="530,493,649,581" href="AD.html"/>

        <!--链接到其他项目的入口-->
        <area shape="rect" coords="530,203,774,291" href="__ROOT__/unistu.php"/>

        <area shape="rect" coords="656,298,773,385" href="../gc/ns.html"/>
        <area shape="rect" coords="10,492,254,580" href="../zyfw/zyfw.html"/>
        <area shape="rect" coords="277,493,395,582" href="__ROOT__/tshc.php"/>
        <area shape="rect" coords="403,493,520,579" href="404.html"/>
        <area shape="rect" coords="864,412,932,440" href="../pphz/rmhb.html"/>
        <area shape="rect" coords="935,410,1004,438" href="../pphz/bjtrt.html"/>
        <area shape="rect" coords="864,450,931,478" href="../pphz/lenovo.html"/>
        <area shape="rect" coords="935,451,1003,478" href="http://www.navigation.com.cn/bhc/index.shtml"/>
    </map>
</div>


<!--底部信息 begin-->
<!--底部信息 begin-->
<div id=footer class="bgcolor">
    <ul class="footpic">
        <li><a href="http://www.news.cn" title="新华通讯社" target="_blank"><img src="__PUBLIC__/images/xhlogo.png"
                                                                            class="pngFix"/></a></li>
        <li><a href="http://www.chnphoto.com.cn" title="新华通讯社" target="_blank"><img
                src="__PUBLIC__/images/cpslogo.png" class="pngFix"/></a></li>
        <br class="clear"/>
    </ul>
    <div class="footurl"><p><a href="__PUBLIC__/footer/about.html">关于我们</a> | <a
            href="__PUBLIC__/footer/copyright.html">版权声明</a> | <a href="__PUBLIC__/footer/connect.html">联系我们</a></p>
    </div>
    <div class="copyright"><p class="footurl">版权所有 © 中国图片社 备案序号：<a href="http://www.miibeian.gov.cn/"
                                                                   target="_blank">京ICP备11020442号</a> | Copyright
        2012-2013 China Photo Service. All Rights Reserved.</p>

        <p class="forbid">本网站内容未经许可不得以任何形式转载</p></div>
</div>
<!--底部信息 end-->
<!--底部信息 end-->

</body>
</html>