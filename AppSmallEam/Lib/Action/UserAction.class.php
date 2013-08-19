<?php
//include(dirname(__FILE__) . '/../Biz/UserBiz.class.php');
Import('@.Biz.UserBiz');

class UserAction extends BaseAction
{
    protected $forward;

    public function _initialize()
    {
        if (!empty($_REQUEST['forward'])) {
            $this->forward = $_GET['forward'] . $_POST['forward'];
        }
        else {
            //if(MODULE_NAME != 'Register' || MODULE_NAME != 'Login' )
            //    $this->forward = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] :  $this->Config['site_url'];
        }

        $this->assign('forward', $this->forward);
        $this->assign('module_name', MODULE_NAME);
        $this->assign('action_name', ACTION_NAME);
    }

    //region ----注册
    public function register()
    {
        //$this->assign('username', '');
        //$this->assign('password1', '');
        //$this->assign('password2', '');
        //$this->assign('email', '');
        $this->assign('verifyCode', '');

        $this->assign('usernamemsg', '');
        $this->assign('password1msg', '');
        $this->assign('password2msg', '');
        $this->assign('emailmsg', '');
        $this->assign('verifymsg', '');

        $this->display();
    }

    public function doregister()
    {
        $userdata = UserBiz::get_safe_data_for_register($_POST);

        $biz = new UserBiz();
        $result = $biz->register($userdata);
        switch ($result) {
            case -1:
                $this->reload_register($userdata, $biz->getError(), $biz->getDetailErrors());
                die();
                break;
            case -2:
                $this->error('注册失败:' . $biz->getError());
                break;
            case -3:
                $this->error('注册失败:发送验证邮件失败!');
                break;
            default:
                $encryptCode = $biz->generate_authcode_for_cookie($userdata);
                setcookie('cookieforresend', $encryptCode, time() + 3600 * 24 * 3); //保存起来，便于重发，验证成功后删除
                $this->assign('send_ok', 1);
                $this->assign('username', $userdata['username']);
                $this->assign('email', $userdata['email']);
                $this->display('emailcheck');
        }
    }

    function reload_register($userdata = array(), $err, $errs = array())
    {
        $this->assign('username', $userdata['username']);
        //$this->assign('password1', $userdata['password1']);
        //$this->assign('password2', $userdata['password2']);
        $this->assign('email', $userdata['email']);
        $this->assign('verifyCode', $userdata['verifyCode']);

        $this->assign('usernamemsg', $errs['username']);
        $this->assign('password1msg', $errs['password1']);
        $this->assign('password2msg', $errs['password2']);
        $this->assign('emailmsg', $errs['email']);
        $this->assign('verifymsg', $errs['verifyCode']);

        $this->display('register');
    }

    //endregion

    //region ----登录
    function login()
    {
        $forward = $this->getForward();
        $this->assign('forward', $forward);
        $this->assign('usernamemsg', '');
        $this->assign('passwordmsg', '');
        $this->assign('verifymsg', '');

        $this->display();
    }

    private function getForward()
    {
      return $_GET['forward'] . $_POST['forward'];
    }

    function dologin()
    {
        $input = UserBiz::get_safe_data_for_login($_POST);

        $biz = new UserBiz();
        $result = $biz->login($input);
        if (false === $result) {
            $this->reloadLogin($input, $biz->getDetailErrors());
            die();
        }

        //保存登录信息
        //$dao = M('User');
        //$data = array();
        //$data['id'] = $authInfo['id'];
        //$data['last_logintime'] = time();
        //$data['last_ip'] = get_client_ip();
        //$data['login_count'] = array('exp', 'login_count+1');
        //$dao->save($data);

        $forward = $input['forward'];
        $this->forward($forward);
    }

    function reloadLogin($userdata = array(), $errs = array())
    {
        $this->assign('username', $userdata['username']);
        $this->assign('usernamemsg', $errs['username']);
        $this->assign('verifymsg', $errs['verifyCode']);
        $this->assign('forward', $userdata['forward']);

        $this->display('login');
    }

    private function forward($forward){
        $forward = strtolower($forward);
        switch($forward){
            case 'upload':
                header('Location: ' . 'unistu.php');
                //$this->redirect('index','Index','','App_Unistu',array(),2,'登陆成功，两秒后跳转');
                break;
            default:
                $this->redirect("Index/index");
        }
    }

    //endregion

    //region ----取回密码
    function getpwd()
    {
        $this->assign('username', '');
        $this->assign('email', '');
        $this->assign('verifyCode', '');

        $this->assign('usernamemsg', '');
        $this->assign('emailmsg', '');
        $this->assign('verifymsg', '');

        $this->display('retrieve');
    }

    function dogetpwd()
    {
        $inputData = UserBiz::get_safe_data_for_register($_POST);

        $biz = new UserBiz();
        $result = $biz->getpwd($inputData);
        switch ($result) {
            case -3:
                $this->reload_getpwd($inputData, $biz->getError(), $biz->getDetailErrors());
                die();
                break;
            case -2:
                $this->error($biz->getError());
                break;
            case -1:
                $this->error($biz->getError());
                break;
            default:
                {
                $this->assign('jumpUrl', U('User/login')); //转向登录
                $this->assign('waitSecond', 8);
                $this->success('尊敬的会员:<br>密码信息已发送至您的邮箱，请用新密码登录并尽快修改密码！如果没有收到密码重置邮件，请到垃圾邮箱中找找看！');
                }
        }
    }

    function reload_getpwd($userdata = array(), $error, $detailErrors = array())
    {
        $this->assign('username', $userdata['username']);
        $this->assign('email', $userdata['email']);
        $this->assign('verifyCode', $userdata['verifyCode']);

        $this->assign('usernamemsg', $detailErrors['username']);
        $this->assign('emailmsg', $detailErrors['email']);
        $this->assign('verifymsg', $detailErrors['verifyCode']);

        $this->display('retrieve');
    }

    //endregion

    //region ----重发激活邮件
    function resendemail()
    {
        if ($_REQUEST['resend']) {
            //从cookie里面取数据
            $encryptCode = $_COOKIE['cookieforresend'];
            if (empty($encryptCode)) {
                $this->error('链接已失效');
                return;
            }

            $biz = new UserBiz();
            $result = $biz->resendemail($encryptCode);
            if (false === $result) {
                $this->error('注册失败:发送认证邮件失败');
            }
            else {
                $this->assign('send_ok', true);
                $this->assign('username', $result['username']);
                $this->assign('email', $result['email']);
                $this->display('emailcheck');
            }
        }
    }

    //endregion

    //region ----邮件激活验证
    /**
     *激活邮件中的url
     * 链接已失效:1.超出解密时间；2.数据中记录已删除
     */
    function regcheckemail()
    {
        $base64Code = $_REQUEST['code'];
        $encryptCode = base64_decode($base64Code);
        $key = UserBiz::generate_authcode_key();
        $plainCode = authcode($encryptCode, 'DECODE', $key);
        if (empty($plainCode)) {
            $this->error('链接已失效');
            return;
        }

        list($userid, $username, $email) = explode("-", $plainCode);
        $dao = M('cps_user');
        $data = $dao->where("uid='{$userid}' and uname='{$username}' and uemail='{$email}'")->find();
        if ($data == false) {
            $this->error('链接已失效');
            return;
        }
        if (C('USER_STATUS_ACTIVED') == $data["status"]) { //已经激活了
            //unset($_COOKIE['cookieforresend']);//删除cookie，不能重发邮件了
            setcookie("cookieforresend", "", time() - 3600);
            $this->assign('jumpUrl', U('User/login')); //转向登录
            $this->assign('waitSecond', 8);
            $this->success('邮箱认证成功！您已成为正式注册会员！请登录进入');
            return;
        }

        $data["status"] = C('USER_STATUS_ACTIVED'); //0:注册未激活；1：已激活；[超出3天未激活的如何处理:删除，需要管理程序]
        $dao->save($data);

        setcookie("cookieforresend", "", time() - 3600);

        $this->assign('jumpUrl', U('User/login')); //转向登录
        $this->assign('waitSecond', 8);
        $this->success('邮箱认证成功！您已成为正式注册会员！请登录进入');
    }

    //endregion

    //region ----退出
    function logout()
    {
        setcookie('auth', NULL, -3600);
        if (isset($_SESSION['logineduser'])) {
            unset($_SESSION['logineduser']);
            unset($_SESSION);
        }
        session_destroy();
        $this->redirect("index.php?m=Index&a=index");
    }

    //endregion


}




