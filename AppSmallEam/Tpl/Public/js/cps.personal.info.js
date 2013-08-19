/**
 * Created with JetBrains PhpStorm.
 * User: chenhongqi
 * Date: 13-7-22
 * Time: 上午9:47
 * To change this template use File | Settings | File Templates.
 */

var cps_personal_info;
if(!cps_personal_info) cps_personal_info={};

jQuery(document).ready(function () {

    bind_event_to_modifybutton();
    bind_event_to_file_input();
});

/*--------------------------------------------------------------------*
 * 给按钮btnModify加事件                                         *
 *--------------------------------------------------------------------*/
function bind_event_to_modifybutton() {

    jQuery(".btnModify").unbind('click');
    jQuery(".btnModify").click(function () {
        var $form = jQuery(this).parents('form');
        $form.ajaxSubmit({
            url         : 'index.php?m=Center&a=domodify',
            type        : "post",
            dataType    : 'json',
            cache       : false,
            beforeSubmit: function (formData, jqForm, options) {
                return cps_personal_info.beforeSubmit($form);
            },
            success     : function (data, st) {
                cps_personal_info.uploadsuccess(data, st, $form);
            },
            error       : function (XMLHttpRequest, textStatus, errorThrown) {
                //alert(textStatus + errorThrown.message);
                //var result = eval("("+XMLHttpRequest.responseText+")");
                cps_personal_info.ajaxerror($form);
            }
        });
    });
}



/*--------------------------------------------------------------------*
 * 表单ajax提交前的动作：用来验证数据                                        *
 *--------------------------------------------------------------------*/
cps_personal_info.beforeSubmit= function($form) {
    $form.find('.err.summary').html('');

    if (cps_personal_info.is_validate_data($form)) {
        $form.find('.err.summary').text("正在上传...");
        $form.find('.imgstatus').attr('src', './Public/images/uploading.gif');
        return true;
    }
    else {
        $form.find('.imgstatus').attr('src', './Public/images/failer.png');
        return false;
    }
}

/*--------------------------------------------------------------------*
 * 数据检查                                                            *
 *--------------------------------------------------------------------*/
cps_personal_info.is_validate_data = function($form) {
    var email = $form.find('input[name=email]').eq(0).val();
    if (email) {
        if (false == isEmail(email)) {
            $form.find('.err.summary').html('您输入的email地址格式不对！');
            return false;
        }
    }

    var idnumber = $form.find('input[name=idnumber]').eq(0).val();
    if (idnumber) {
        if (false == isIdCardNo(idnumber)) {
            $form.find('.err.summary').html('您输入的身份证号码格式不对！');
            return false;
        }
    }

    var mobile = $form.find('input[name=tel1]').eq(0).val();
    if (mobile) {
        if (false == isMobile(mobile)) {
            $form.find('.err.summary').html('您输入的手机号码格式不对！');
            return false;
        }
    }

    var birthday = $form.find('input[name=birthday]').eq(0).val();
    if (birthday) {
        if (false == isDate(birthday)) {
            $form.find('.err.summary').html('您输入的出生日期格式不对！');
            return false;
        }
    }


    return true;
}



/*--------------------------------------------------------------------*
 * 表单提交成功后的动作：显示失败信息或成功信息                            *
 *--------------------------------------------------------------------*/
cps_personal_info.uploadsuccess=function (data, statusText, $form) {

    $form.find('.err.summary').html(data.info);

    if (data.status === 0) {//失败
        //todo：给出清楚的提示
        var errs = data.data;
        var str = '';
        for (err in errs) {
            str += errs[err]+'<br>';
        }
        $form.find('.err.summary').html(str);
        //$form.find('.err.summary').html(data.info);
        $form.find('.imgstatus').attr('src', './Public/images/failer.png');
        return false;
    }

    var upinfo = data.data;
    var imgsrc = upinfo.avatar;
    $form.find('.avatar').attr('src', imgsrc);
    $form.find('.err.summary').html(data.info);
    $form.find('.imgstatus').attr('src', './Public/images/success.png');
    $form.find('input[name=avatarstatus]').val('uploaded');
}


/*--------------------------------------------------------------------*
 * 处理ajax出错                                                        *
 *--------------------------------------------------------------------*/
cps_personal_info.ajaxerror = function ($form) {
    $form.find('.err.summary').html("远程调用失败!");
    $form.find('.imgstatus').attr('src', './Public/images/failer.png');
}



function bind_event_to_file_input() {
    jQuery(":file").unbind('change');
    jQuery(":file").change(function () {
        var $form = jQuery(this).parents('form');
        $form.find('input[name=avatarstatus]').eq(0).val('changed');
    });
}


