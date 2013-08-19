<?php
require_once 'base.php';
require_once 'MyDataProvider.php';
import("App_Home.Model.UserModel");
import("App_Home.Biz.UserBiz");
import("App_Unistu.Model.ProductModel");
//import("App_Unistu.Common.common");
include(dirname(__FILE__) . '/../../../App_Unistu/Common/common.php');

class UserBizTest extends PHPUnit_Framework_TestCase
{
    protected $pdo;

    public function __construct()
    {
        try {
            //注意和配置文件的数据库设置保持一致
            $this->pdo = new PDO(DB_NAME, DB_USER, DB_PWD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        }
        catch (PDOException $e) {
            echo "连接数据库失败：" . $e->getMessage();
        }
    }

    protected function setUp()
    {
        MyDataProvider::init_data();
    }

    public function test_user_register_ok()
    {
        $_SERVER['HTTP_HOST'] = 'www.chnphoto.cn';
        $userdata = array('username' => 'wangjiang',
            'password1' => '1234',
            'password2' => '1234',
            'email' => 'chenhongqi@sina.com',
            'verifyCode' => '5432');
        $_SESSION['verify'] = md5('5432');

        $biz = new UserBiz();
        $result = $biz->register($userdata);

        $this->assertEquals($result, 1);

    }

    public function test_user_register_double_account()
    {
        $_SERVER['HTTP_HOST'] = 'www.chnphoto.cn';
        $userdata = array('username' => 'chenhongqi',
            'password1' => '1234',
            'password2' => '1234',
            'email' => 'chenhongqi@sina.com',
            'verifyCode' => '5432');
        $_SESSION['verify'] = md5('5432');

        $biz = new UserBiz();
        $result = $biz->register($userdata);

        $this->assertEquals($result, -1);
        print $biz->getError() . '\n';
        echo '\n';
        dump($biz->getDetailErrors());

    }

    public function test_login_ok()
    {
        $input = array(
            'username' => 'chenhongqi',
            'password' => '1',
            'email' => 'chenhongqi@sina.com',
            'verifyCode' => '5432');
        $_SESSION['verify'] = md5('5432');

        $biz = new UserBiz();
        $result = $biz->login($input);
        $this->assertEquals($result, true);
    }

    public function test_login_failed(){
        $input = array(
            'username' => 'chenhongqi',
            'password' => '2',
            'email' => 'chenhongqi@sina.com',
            'verifyCode' => '5432');
        $_SESSION['verify'] = md5('5432');

        $biz = new UserBiz();
        $result = $biz->login($input);
        $this->assertEquals($result,false);
        print $biz->getError() . '\n';
        echo '\n';
        dump($biz->getDetailErrors());
    }

}