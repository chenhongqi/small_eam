<?php
include_once WEB_ROOT . 'Common/common.php';


/**********
 * 发送邮件 *
 **********/
function SendMail($address, $title, $message)
{
    vendor('PHPMailer.class#phpmailer');

    $mail = new PHPMailer();
    // 设置PHPMailer使用SMTP服务器发送Email
    $mail->IsSMTP();

    // 设置邮件的字符编码，若不指定，则为'UTF-8'
    $mail->CharSet = 'UTF-8';

    // 添加收件人地址，可以多次使用来添加多个收件人
    $mail->AddAddress($address);

    // 设置邮件正文
    $mail->Body = $message;

    // 设置邮件头的From字段。
    $mail->From = C('MAIL_ADDRESS');

    // 设置发件人名字
    $mail->FromName = C('MAIL_SENDER');
    // 设置邮件标题
    $mail->Subject = $title;

    // 设置SMTP服务器。
    $mail->Host = C('MAIL_SMTP');

    // 设置为“需要验证”
    $mail->SMTPAuth = true;

    // 设置用户名和密码。
    $mail->Username = C('MAIL_LOGINNAME');
    $mail->Password = C('MAIL_PASSWORD');

    // 发送邮件。
    return ($mail->Send());
}


function authcode($string, $operation = 'DECODE', $key = "", $expiry = 0)
{
    //$key是自定义的一个密钥
    $ckey_length = 4;
    $key = md5($key ? $key : '123456'); //若未指定key，则使用123456，可以改成自己的
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        }
        else {
            return '';
        }
    }
    else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }

}

function generate_password($length = 8)
{
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-~?';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[mt_rand(0, strlen($chars) - 1)];
    }

    return $password;
}


