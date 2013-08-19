/**
 * Created with JetBrains PhpStorm.
 * User: chenhongqi
 * Date: 13-7-22
 * Time: 上午9:47
 * To change this template use File | Settings | File Templates.
 */


var cps_personal_reset;
if(!cps_personal_reset) cps_personal_reset={};

jQuery(document).ready(function () {
    bind_event_to_chgpasswordbutton();
});

/*--------------------------------------------------------------------*
 * 给按钮btnChgPwd加事件                                               *
 *--------------------------------------------------------------------*/
function bind_event_to_chgpasswordbutton() {
    jQuery("#btnChgPwd").unbind('click');
    jQuery("#btnChgPwd").click(function () {
        var $form = jQuery(this).parents('form');
        $form.ajaxSubmit({
            url         : 'index.php?m=Center&a=doresetpwd',
            type        : "post",
            dataType    : 'json',
            cache       : false,
            beforeSubmit: function (formData, jqForm, options) {
                return cps_personal_reset.beforeSubmit($form);
            },
            success     : function (data, st) {
                cps_personal_reset.uploadsuccess(data, st, $form);
            },
            error       : function (XMLHttpRequest, textStatus, errorThrown) {
                //alert(textStatus + errorThrown.message);
                //var result = eval("("+XMLHttpRequest.responseText+")");
                cps_personal_reset.ajaxerror($form);
            }
        });
    });
}


/*--------------------------------------------------------------------*
 * 表单ajax提交前的动作：用来验证数据                                     *
 *--------------------------------------------------------------------*/
cps_personal_reset.beforeSubmit = function($form) {
    $form.find('.err.summary').html('');
    if (cps_personal_reset.is_validate_data($form)) {
        $form.find('.err.summary').text("正在修改密码...");
        return true;
    }
    else {
        return false;
    }
}

/*--------------------------------------------------------------------*
 * 数据检查                                                            *
 *--------------------------------------------------------------------*/
cps_personal_reset.is_validate_data =function($form) {
    var password1 = $form.find('input[name=password1]').eq(0).val();
    var password2 = $form.find('input[name=password2]').eq(0).val();
    var verifyCode = $form.find('input[name=verifyCode]').eq(0).val();

    if (!password1) {
        $form.find('.err.summary').html('请输入新密码！');
        return false;
    }
    if (!password2) {
        $form.find('.err.summary').html('请再次输入新密码！');
        return false;
    }

    if (!verifyCode) {
        $form.find('.err.summary').html('请输入验证码！');
        return false;
    }

    if (password1 !== password2) {
        $form.find('.err.summary').html('您两次输入的密码不一样！请确认您的密码！');
        return false;
    }


    return true;
}


/*--------------------------------------------------------------------*
 * 表单提交成功后的动作：显示失败信息或成功信息                            *
 *--------------------------------------------------------------------*/
cps_personal_reset.uploadsuccess = function(data, statusText, $form) {

    $form.find('.err.summary').html(data.info);

    if (data.status === 0) {//失败
        var errs = data.data;
        var str = '';
        for (err in errs) {
            str += errs[err];
        }
        $form.find('.err.summary').html(str);
        return false;
    }

    $form.find('.err.summary').html(data.info);
}

/*--------------------------------------------------------------------*
 * 处理ajax出错                                                        *
 *--------------------------------------------------------------------*/
cps_personal_reset.ajaxerror = function($form) {
    $form.find('.err.summary').html("远程调用失败!");
}



