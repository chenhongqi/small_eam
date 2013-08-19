<?php
class UserBiz
{
    private $_error;
    private $_detail_errors;

    public function getDetailErrors()
    {
        return $this->_detail_errors;
    }

    public function getError()
    {
        return $this->_error;
    }

    public static function get_safe_data_for_register($unsafe)
    {
        $safe = array();
        $safe["username"] = isset($unsafe['account']) ? get_safe_replace(trim($unsafe['account'])) : '';
        $safe["password"] = isset($unsafe['password']) ? get_safe_replace(trim($unsafe['password'])) : '';
        $safe["password1"] = isset($unsafe['password1']) ? get_safe_replace(trim($unsafe['password1'])) : '';
        $safe["password2"] = isset($unsafe['password2']) ? get_safe_replace(trim($unsafe['password2'])) : '';
        $safe["email"] = isset($unsafe['email']) ? get_safe_replace(trim($unsafe['email'])) : '';
        $safe["verifyCode"] = isset($unsafe['verifyCode']) ? get_safe_replace(trim($unsafe['verifyCode'])) : '';
        return $safe;
    }

    public static function get_safe_data_for_login($unsafe)
    {
        $safe = array();
        $safe["username"] = isset($unsafe['account']) ? get_safe_replace(trim($unsafe['account'])) : '';
        $safe["password"] = isset($unsafe['password']) ? get_safe_replace(trim($unsafe['password'])) : '';
        $safe["verifyCode"] = isset($unsafe['verifyCode']) ? get_safe_replace(trim($unsafe['verifyCode'])) : '';
        $safe["forward"] = isset($unsafe['forward']) ? get_safe_replace(trim($unsafe['forward'])) : '';
        $safe["remberpwd"]=isset($unsafe['remberpwd']);
        return $safe;
    }

    public static function get_safe_data_for_profile($unsafe)
    {
        $safe = array();
        $safe["uemail"] = isset($unsafe['email']) ? get_safe_replace(trim($unsafe['email'])) : '';
        $safe["uIDno"] = isset($unsafe['idnumber']) ? get_safe_replace(trim($unsafe['idnumber'])) : '';
        $safe["ubirthday"] = isset($unsafe['birthday']) ? get_safe_replace(trim($unsafe['birthday'])) : '';
        $safe["utname"] = isset($unsafe['realname']) ? get_safe_replace(trim($unsafe['realname'])) : '';
        $safe["tel1"] = isset($unsafe['tel1']) ? get_safe_replace(trim($unsafe['tel1'])) : '';
        $safe["tel2"] = isset($unsafe['tel2']) ? get_safe_replace(trim($unsafe['tel2'])) : '';
        $safe["usex"] = isset($unsafe['gender']) ? get_safe_replace(trim($unsafe['gender'])) : '';
        $safe["uqq"] = isset($unsafe['qq']) ? get_safe_replace(trim($unsafe['qq'])) : '';
        $safe["address"] = isset($unsafe['address']) ? get_safe_replace(trim($unsafe['address'])) : '';
        $safe["zcode"] = isset($unsafe['zipcode']) ? get_safe_replace(trim($unsafe['zipcode'])) : '';
        $safe["alterdate"] = date('Y-m-d H:i:s');
        $safe["hobby"] = isset($unsafe['hobby']) ? get_safe_replace(trim($unsafe['hobby'])) : '';
        $safe["job"] = isset($unsafe['job']) ? get_safe_replace(trim($unsafe['job'])) : '';
        $safe["about"] = isset($unsafe['about']) ? get_safe_replace(trim($unsafe['about'])) : '';
        $safe["degree"] = isset($unsafe['degree']) ? get_safe_replace(trim($unsafe['degree'])) : '';
        //客户端头像状态：empty，uploaded，changed
        $safe["avatarstatus"] = isset($unsafe['avatarstatus']) ? get_safe_replace(trim($unsafe['avatarstatus'])) : '';

        return $safe;
    }

    function validate_input_for_get_password($safeData = array())
    {
        $this->_detail_errors = array();
        $username = $safeData['username'];
        if (empty($username)) {
            $this->_detail_errors["username"] = '请输入用户名';
        }

        $email = $safeData['email'];
        if (empty($email)) {
            $this->_detail_errors["email"] = '请输入您注册时的email';
        }
        else if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
            $this->_detail_errors["email"] = '请输入正确格式的邮箱';
        }

        $verifyCode = $safeData['verifyCode'];
        if (empty($verifyCode)) {
            $this->_detail_errors["verifyCode"] = '请输入验证码';
        }
        else if (md5($verifyCode) != $_SESSION['verify']) {
            $this->_detail_errors["verifyCode"] = '您输入的验证码不正确';
        }

        //验证用户-邮箱是否匹配
        if ((!empty($username)) && (!empty($email))) {
            $dao = new UserModel();
            $result = $dao->find_user_by_name_and_email($username, $email);
            if (!$result) {
                $this->_detail_errors["email"] = '请输入您注册时使用的邮箱';
            }
        }


        if (count($this->_detail_errors) > 0) {
            $this->_error = '数据验证未通过!';
            return false;
        }

        return true;

    }

    function validate_input_for_reset_password($safeData = array())
    {
        $this->_detail_errors = array();
        $password1 = $safeData['password1'];
        if (empty($password1)) {
            $this->_detail_errors["password1"] = '请输入密码!';
        }

        $password2 = $safeData['password2'];
        if (empty($password2)) {
            $this->_detail_errors["password2"] = '请输入密码!';
        }

        if ((!empty($password2)) && (!empty($password1)) && ($password1 != $password2)) {
            $this->_detail_errors["password2"] = '您两次输入的密码不一样!';
        }


        $verifyCode = $safeData['verifyCode'];
        if (empty($verifyCode)) {
            $this->_detail_errors["verifyCode"] = '请输入验证码!';
        }
        else if (md5($verifyCode) != $_SESSION['verify']) {
            $this->_detail_errors["verifyCode"] = '您输入的验证码不正确!';
        }


        if (count($this->_detail_errors) > 0) {
            $this->_error = '数据验证未通过!';
            return false;
        }

        return true;

    }

    function validate_input_for_register($safeData = array())
    {
        $this->_detail_errors = array();
        $username = $safeData['username'];
        if (empty($username)) {
            $this->_detail_errors["username"] = '请输入用户名';
        }

        $password1 = $safeData['password1'];
        if (empty($password1)) {
            $this->_detail_errors["password1"] = '请输入密码';
        }

        $password2 = $safeData['password2'];
        if (empty($password2)) {
            $this->_detail_errors["password2"] = '请输入密码';
        }

        if ((!empty($password2)) && (!empty($password1)) && ($password1 != $password2)) {
            $this->_detail_errors["password2"] = '您两次输入的密码不一样';
        }

        $email = $safeData['email'];
        if (empty($email)) {
            $this->_detail_errors["email"] = '请输入email';
        }
        else if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
            $this->_detail_errors["email"] = '请输入正确格式的邮箱';
        }

        $verifyCode = $safeData['verifyCode'];
        if (empty($verifyCode)) {
            $this->_detail_errors["verifyCode"] = '请输入验证码';
        }
        else if (md5($verifyCode) != $_SESSION['verify']) {
            $this->_detail_errors["verifyCode"] = '您输入的验证码不正确';
        }

        if (!empty($username)) {
            if ($this->is_exist_account($username)) {
                $this->_detail_errors["username"] = '您输入的用户名已存在';
            }
        }

        if (count($this->_detail_errors) > 0) {
            $this->_error = '数据验证未通过';
            return false;
        }

        return true;
    }

    function validate_input_for_profile_data($safeData)
    {
        $this->_detail_errors = array();
        $email = $safeData['uemail'];
        if (!empty($email)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
                $this->_detail_errors["email"] = '请输入正确格式的邮箱';
            }
        }

        $idnum = $safeData['uIDno'];
        if (!empty($idnum)) {
            if (validation_filter_id_card($idnum) === false) {
                $this->_detail_errors["idnumber"] = '请输入正确的身份证号码';
            }
        }

        $birthday = $safeData['ubirthday'];
        if (!empty($birthday)) {
            if (false == strtotime($birthday)) {
                $this->_detail_errors["birthday"] = '请输入正确的日期';
            }
        }

        $zcode = $safeData['zcode'];
        if (!empty($zcode)) {
            $ab = preg_match("/^[1-9][0-9]{5}$/", $zcode);
            if (false == $ab) {
                $this->_detail_errors["zcode"] = '请输入正确的邮编';
            }
        }

        //手机号码
        $mobile = $safeData['tel1'];
        if (!empty($mobile)) {
            $ab = preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/", $mobile);
            if (false == $ab) {
                $this->_detail_errors["tel1"] = '请输入正确的手机号码';
            }
        }

        if (count($this->_detail_errors) > 0) {
            $this->_error = '数据验证未通过';
            return false;
        }

        return true;
    }


    //region----登录
    function login($input = array())
    {
        $this->_detail_errors = array();
        $username = $input['username'];
        if (empty($username)) {
            $this->_detail_errors["username"] = '请输入用户名';
        }

        $password = $input['password'];
        if (empty($password)) {
            $this->_detail_errors["password"] = '请输入密码';
        }

        $verifyCode = $input['verifyCode'];
        if (empty($verifyCode)) {
            $this->_detail_errors["verifyCode"] = '请输入验证码';
        }
        else if (md5($verifyCode) != $_SESSION['verify']) {
            $this->_detail_errors["verifyCode"] = '您输入的验证码不正确';
        }

        if (count($this->_detail_errors) > 0) {
            $this->_error = '登录失败';
            return false;
        }

        $safeUserName = $username; //mysql_real_escape_string($username);
        $dao = D('User');
        $user = $dao->where("uname='{$safeUserName}'")->find();

        if (($user == false) || ($user == null)) {
            $this->_detail_errors["username"] = '您输入的用户名密码错误';
        }
        else if (count($user) <= 0) {
            $this->_detail_errors["username"] = '您输入的用户名密码错误';
        }
        else if ($user['upw'] != md5($password)) {
            $this->_detail_errors["username"] = '您输入的用户名密码错误';
        }
        else if ($user['status'] == C('USER_STATUS_REGISTER')) {
            $this->_detail_errors["username"] = '您还未进行邮箱验证，不能登录!';
        }
        else if ($user['status'] == C('USER_STATUS_LOCKED')) {
            $this->_detail_errors["username"] = '您的账号被锁定，不能登陆!';
        }
        else {
            $_SESSION["logineduser"] = $user;
            if($input['remberpwd']){
                AutoLoginModel::remberPasswordForAutoLogin($user,false);
            }
            return true;
        }

        $this->_error = '登录失败';
        return false;
    }


    //endregion

    //region----注册
    public function register($userdata)
    {
        if (false === $this->validate_input_for_register($userdata)) {
            return -1;
        }

        $data = $this->insertUser($userdata);
        if (false === $data) {
            return -2;
        }

        $to = $data['uemail'];
        $encryptCode = $this->generate_authcode_for_cookie($data);
        $body = $this->generate_activate_email_body($encryptCode);
        $title = $this->generate_activate_email_title($data);
        $result = $this->sendmail($to, $title, $body);
        if (false === $result) {
            $this->deleteUser($data['uid']); //发送邮件失败，则删除数据库中的记录
            Log::write('[UserBiz]发送邮件失败: ', Log::ERR);
            return -3;
        }

        return 1;
    }

    private function is_exist_account($username)
    {
        $dao = new UserModel();
        $user = $dao->where("uname='{$username}'")->find();
        return !empty($user);
    }

    private function insertUser($userdata)
    {
        //$data = array();
        //$data["uname"] = $userdata["username"];
        //$data["upw"] = md5($userdata["password1"]);
        //$data["uemail"] = $userdata["email"];
        //$data['createdate'] = date('Y-m-d H:i:s');
        //$data['alterdate'] = date('Y-m-d H:i:s');
        //$data['status'] = C('USER_STATUS_REGISTER'); //初始状态:未激活

        $dao = new UserModel();
        $dao->uname = $userdata["username"];
        $dao->upw = md5($userdata["password1"]);
        $dao->uemail = $userdata["email"];
        $dao->createdate = date('Y-m-d H:i:s');
        $dao->alterdate = date('Y-m-d H:i:s');
        $dao->status = C('USER_STATUS_REGISTER'); //初始状态:未激活
        if (false === $dao->add()) {
            $this->setError($dao);
            return false;
        }
        return $dao->data();
    }


    private function deleteUser($userid)
    {
        $dao = new UserModel();
        $dao->delete_user_by_id($userid);
    }

    private function setError(Model $m)
    {
        $err = $m->getError();
        $this->_error = empty($err) ? $m->getDbError() : $err;
    }


    public function generate_authcode_for_cookie($user)
    {
        $key = $this->generate_authcode_key();
        $encryptCode = authcode($user['uid'] . "-" . $user['uname'] . "-" . $user['uemail'], 'ENCODE', $key, 3600 * 24 * 3); //3天有效期  [问题：加密会出现斜线]
        return $encryptCode;
    }

    private function generate_activate_email_body($encryptCode)
    {
        $base64Code = base64_encode($encryptCode);
        $url = 'http://' . $_SERVER['HTTP_HOST'] . U('User/regcheckemail?code=' . $base64Code);
        $click = "<a href=\"$url\" target=\"_blank\">" . L('CLICK_THIS') . "</a>";
        $tpl = ' 欢迎您注册成为{sitename}用户，您的账号需要邮箱认证，点击下面链接进行认证：{click}\r\n或者将网址复制到浏览器：{url}';
        $message = str_replace(array('{click}', '{url}', '{sitename}'), array($click, $url, C('SITE_NAME')), $tpl);
        return $message;
    }

    private function generate_activate_email_title()
    {
        $title = '注册认证邮件' . '-' . C('SITE_NAME');
        return $title;
    }


    public static function generate_authcode_key()
    {
        //$key = md5(C('AUTH_KEY') . $_SERVER['HTTP_USER_AGENT']);
        $key = C('AUTH_KEY');
        return $key;
    }

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
        $result = $mail->Send();
        if (false === $result) {
            $this->_error = $mail->ErrorInfo;
        }
        return $result;
    }

    //endregion

    //region----重发激活邮件
    public function resendemail($encryptCode)
    {
        $key = $this->generate_authcode_key();
        list($userid, $username, $email) = explode("-", authcode($encryptCode, 'DECODE', $key));
        $userdata = array('uid' => $userid, 'username' => $username, 'email' => $email);

        $encryptCode = $this->generate_authcode_for_cookie($userdata);
        $body = $this->generate_activate_email_body($encryptCode);
        $title = $this->generate_activate_email_title($userdata);
        $result = $this->sendmail($email, $title, $body);
        if (false === $result) {
            $this->deleteUser($userdata['uid']); //发送邮件失败，则删除数据库中的记录
            Log::write('[UserBiz]发送邮件失败: ', Log::ERR);
            return false;
        }

        return $userdata;
    }

    //endregion

    //region ----取回密码
    public function getpwd($inputData)
    {
        //1.验证数据
        if (false === $this->validate_input_for_get_password($inputData)) {
            return -3;
        }

        //2.生成一个临时密码
        $newpwd = generate_password();
        //3向用户邮箱发送密码
        $message = "系统为您找回密码是：{$newpwd} 请用此密码登录并立即修改密码。";
        $r = sendmail($inputData['email'], '找回密码邮件' . '-' . C('SITE_NAME'), $message);
        if ($r) {
            //4修改数据库中的密码
            $result = $this->reset_password_by_account($inputData['userName'], $newpwd);
            if (!$result) {
                $this->_error = '取回密码失败:未找到对应的账号';
                return -2;
            }
            return 4;
        }
        else {
            $this->_error = ('取回密码失败:发送密码邮件失败');
            return -1;
        }
    }

    private function reset_password_by_account($userName, $password)
    {
        $dao = new UserModel();
        $pwd = md5($password); //md5加密
        $result = $dao->reset_password_by_account($userName, $pwd);
        return $result;
    }

    //endregion

    //region----重置密码
    public function resetPassword($inputData)
    {
        //1.验证数据
        $ret = $this->validate_input_for_reset_password($inputData);
        if (false === $ret) {
            return -1;
        }

        //2.修改密码
        $dao = new UserModel();
        $userid = $this->get_logined_user_id();
        $result = $dao->reset_password_by_id($userid, $inputData['password1']);
        if (false === $result) {
            return -2;
        }
        else {
            return 1;
        }
    }

    //endregion

    //region----修改用户信息
    public function modifyUser($safeData = array())
    {
        //1.数据检查
        if (false === $this->validate_input_for_profile_data($safeData)) {
            return -1;
        }

        //2.头像上传
        $safeData = $this->uploadAvatar($safeData);
        if (false === $safeData) {
            return -2;
        }

        $userid = $this->get_logined_user_id();
        $dao = new UserModel();
        $user = $dao->findUser($userid);
        $originalAvatar = $user['avatar'];
        //3.更新数据
        $newData = array_merge($user, $safeData);
        $result = $dao->save($newData);
        if (false === $result) {
            $this->setError($dao);
            return -3;
        }

        //4.删除原来的照片文件
        $this->deleteOriginalAvatar($originalAvatar, $safeData);

        return $newData;
    }

    private function uploadAvatar($safeData)
    {
        if ($safeData["avatarstatus"] == 'changed') {
            import('ORG.Net.AvatarUploadFile');
            $upload = new AvatarUploadFile();
            if (false === $upload->upload()) {
                $this->_error = $upload->getErrorMsg();
                return false;
            }
            $info = $upload->getUploadFileInfo();
            $safeData['avatar'] = AvatarUploadFile::relative_path($info[0]['savepath'] . $info[0]['savename']);
            if (isset($info[0]['thumbs'])) {
                $safeData['avatar'] = AvatarUploadFile::relative_path($info[0]['thumbs'][0]);
            }
        }
        return $safeData;
    }

    private function deleteOriginalAvatar($originalAvatar, $safeData)
    {
        if (isset($safeData['avatar'])) {
            if ($originalAvatar != $safeData['avatar']) {
                $this->deleteAvatar($originalAvatar);
            }
        }
    }

    private function deleteAvatar($originalAvatar)
    {
        $original = C('MOUNT_DIR') . $originalAvatar;
        if (is_file($original)) {
            if (false === unlink($original)) {
                Log::write('[unistu]删除上传的照片失败: ' . $original, Log::WARN);
            }
        }
    }


    //endregion

    public function lockUser($userid)
    {
        //if (!Authentication::checkAuthentication()) {
        //    $this->error='权限不足';
        //    return false;
        //}


        $user = $this->findUser($userid);
        if (false == $user) {
            $this->_error = '锁定失败:没有此用户';
            return false;
        };

        $dao = new UserModel();
        $ret = $dao->change_user_status($userid,C('USER_STATUS_LOCKED'));
        if (false === $ret) {
            $this->setError($dao);
            return false;
        }

        $M = M("cps_user_status_changed");
        $data = $this->set_user_status_changed_data($user);
        $M->add($data);

        return true;
    }

    private function set_user_status_changed_data($user)
    {
        $data = array();
        $data['userid'] = $user['uid'];
        $data['username'] = $user['uname'];
        $data['status'] = C('USER_STATUS_LOCKED');
        $data['changedtime'] = date('Y-m-d H:i:s');
        $loginedUser = $this->get_logined_user();
        $data['changedby'] = $loginedUser['uname'];
        return $data;
    }

    public static function findUser($userid)
    {
        $dao = D("User");
        return $dao->where("uid={$userid}")->find();
    }


    static function get_logined_user()
    {
        return $_SESSION['logineduser'];
    }

    static function get_logined_user_id()
    {
        $user = $_SESSION['logineduser'];
        return $user['uid'];
    }

    static public function is_logined()
    {
        return isset($_SESSION['logineduser']);
    }

}