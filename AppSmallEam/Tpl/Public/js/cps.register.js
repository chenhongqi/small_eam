/**
 * Created with JetBrains PhpStorm.
 * User: chenhongqi
 * Date: 13-7-26
 * Time: 下午4:42
 * To change this template use File | Settings | File Templates.
 */

jQuery(document).ready(function () {
    jQuery("body").keypress(function (event) {
        switch (event.keyCode) {
            case 13:
                jQuery("#buttonzhuce").trigger("click");
        }
    });

    jQuery('#buttonzhuce').click(function (event) {

        if (document.getElementById("protocol").checked != true) {
            event.preventDefault();
            return false;
        }
        var account = jQuery('#account').val().trim();
        var password1 = jQuery('#password1').val().trim();
        var password2 = jQuery('#password2').val().trim();
        var email = jQuery('#email').val().trim();
        var verifycode = jQuery('#verifycode').val().trim();

        var ret = true;
        jQuery('#usernamemsg').text('');
        if (isEmpty(account)) {
            jQuery('#usernamemsg').text("请输入用户名");
            ret = false;
        }
        jQuery('#password1msg').text('');
        jQuery('#password2msg').text('');
        if (isEmpty(password1)) {
            jQuery('#password1msg').text("请输入密码");
            ret = false;
        }
        else if (isEmpty(password2)) {
            jQuery('#password2msg').text("请输入密码");
            ret = false;
        }
        else if (password1 != password2) {
            jQuery('#password2msg').text("您两次输入的密码不一致");
            ret = false;
        }
        jQuery('#emailmsg').text('');
        if (isEmpty(email)) {
            jQuery('#emailmsg').text("请输入email");
            ret = false;
        }
        else if (!isEmail(email)) {
            jQuery('#emailmsg').text("email格式不正确");
            ret = false;
        }
        jQuery('#verifycodemsg').text('');
        if (isEmpty(verifycode)) {
            jQuery('#verifycodemsg').text("请输入验证码");
            ret = false;
        }

        if (ret == false) {
            return false;
        }

//提交表单
        jQuery('form').submit();
        event.preventDefault();
        return false;
    });
});

/********************************** Empty **************************************/
/**
 *校验字符串是否为空
 *返回值：
 *如果不为空，定义校验通过，返回true
 *如果为空，校验不通过，返回false               参考提示信息：输入域不能为空！
 */
function isEmpty(str) {
    return (str == '') || (str == 'chavroka') || (typeof(str) == "undefined");
}
/*--------------------------------- Empty --------------------------------------*/

/********************************** Email **************************************/
function isEmail(value) {
    var Reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    return Reg.test(value);
}
/*--------------------------------- Email --------------------------------------*/
