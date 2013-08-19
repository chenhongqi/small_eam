<?php

require_once 'base.php';
require_once 'MyDataProvider.php';
import("App_Home.Model.UserModel");
import("App_Home.Biz.UserBiz");

class UserBiz2Test extends PHPUnit_Framework_TestCase
{

    protected $pdo;

    public function __construct() {

        try {
            //注意和配置文件的数据库设置保持一致
            $this->pdo = new PDO(DB_NAME, DB_USER, DB_PWD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

            //$this->pdo->query()
        }
        catch (PDOException $e) {
            echo "连接数据库失败：" . $e->getMessage();
        }

    }

    protected function setUp()
    {
        MyDataProvider::init_data();
    }

    public function test_validate_register_input_ok()
    {
        $inputData = array('username' => 'hongqi',
            'password1' => '1',
            'password2' => '1',
            'email' => 'chenhongqi@sian.com',
            'verifyCode' => '5432');
        $_SESSION['verify'] = md5('5432');

        $biz = new UserBiz();
        $result = $biz->validate_input_for_register($inputData);
        $this->assertTrue($result);

    }

    public function test_validate_register_input_double_name()
    {
        $inputData = array('username' => 'chenhongqi',
            'password1' => '1',
            'password2' => '1',
            'email' => 'chenhongqi@sian.com',
            'verifyCode' => '5432');
        $_SESSION['verify'] = md5('5432');

        $biz = new UserBiz();
        $result = $biz->validate_input_for_register($inputData);
        $this->assertFalse($result);

        $detailErrors = $biz->getDetailErrors();
        $errmsg = $biz->getError();

        $this->assertEquals('数据验证未通过', $errmsg);
        $this->assertEquals('您输入的用户名已存在', $detailErrors['username']);


    }

    public function test_add_user()
    {
        $inputData = array('username' => 'wangzhigang',
            'password1' => '1',
            'password2' => '1',
            'email' => 'chenhongqi@sian.com',
            'verifyCode' => '5432');
        $_SESSION['verify'] = md5('5432');

        $biz = new UserBiz();
        $result = $biz->validate_input_for_register($inputData);
        $this->assertTrue($result);

        $exp = $biz->register($inputData);
        $this->assertTrue($exp == 1);

        //$data = $biz->findUser($exp);
        //$this->assertEquals('chenhongqi@sian.com', $data['uemail']);
        //$this->assertEquals('wangzhigang', $data['uname']);

    }



    public function test_validate_profile_data(){
        $inputData = array('uemail' => '@sina.com',
            'uIDno' => '420123199001183372',
            'ubirthday' => '199-09-09',
            'utname' => '汪峰',
            'tel1' => '12426480060',
            'tel2' => '13426480060',
            'usex' => '男',
            'uqq' => '902303232',
            'address' => '北京昌平回龙观;',
            'zcode' => '10228',
            'hobby' => '篮球、羽毛球',
            'job' => '程序员、歌手',
            'about' => '爱好和平的人',
            'degree' => '打本',
            'avatarstatus' => '');

        $biz = new UserBiz();
        $result = $biz->validate_input_for_profile_data($inputData);
        $this->assertFalse($result);

        $this->assertEquals($biz->getError(),'数据验证未通过');
        $errors = $biz->getDetailErrors();
        $this->assertEquals($errors['email'],'请输入正确格式的邮箱');

        $inputData['uemail']='jjj@sina.com';
        $result = $biz->validate_input_for_profile_data($inputData);
        $this->assertFalse($result);

        $this->assertEquals($biz->getError(),'数据验证未通过');
        $errors = $biz->getDetailErrors();
        $this->assertEquals($errors['idnumber'],'请输入正确的身份证号码');

        $inputData['uIDno']='420123197001183372';
        $result = $biz->validate_input_for_profile_data($inputData);
        $this->assertFalse($result);

        $this->assertEquals($biz->getError(),'数据验证未通过');
        $errors = $biz->getDetailErrors();
        $this->assertEquals($errors['birthday'],'请输入正确的日期');

        $inputData['ubirthday']='1980-08-08';
        $result = $biz->validate_input_for_profile_data($inputData);
        $this->assertFalse($result);

        $this->assertEquals($biz->getError(),'数据验证未通过');
        $errors = $biz->getDetailErrors();
        $this->assertEquals($errors['tel1'],'请输入正确的手机号码');

        $inputData['tel1']='13426480060';
        $result = $biz->validate_input_for_profile_data($inputData);
        $this->assertFalse($result);

        $this->assertEquals($biz->getError(),'数据验证未通过');
        $errors = $biz->getDetailErrors();
        $this->assertEquals($errors['zcode'],'请输入正确的邮编');

        $inputData['zcode']='100228';
        $result = $biz->validate_input_for_profile_data($inputData);
        $this->assertTrue($result);

    }



    public function test_modify_user()
    {
        $userid = 3;
        $biz = new UserBiz();
        $_SESSION['logineduser'] = $biz->findUser($userid);
        $post = array('uemail' => 'jjj@sina.com',
            'uIDno' => '420123197001183372',
            'ubirthday' => '1999-09-09',
            'utname' => '汪峰',
            'tel1' => '13426480060',
            'tel2' => '13426480060',
            'usex' => '男',
            'uqq' => '902303232',
            'address' => '北京昌平回龙观;',
            'zcode' => '100228',
            'hobby' => '篮球、羽毛球',
            'job' => '程序员、歌手',
            'about' => '爱好和平的人',
            'degree' => '打本',
            'avatarstatus' => ''
        );


        $exp = $biz->modifyUser($post, $userid);

        $this->assertTrue($exp !== false);

        $data = $biz->findUser($userid);
        $this->assertEquals('jjj@sina.com', $data['uemail']);
        $this->assertEquals('北京昌平回龙观;', $data['address']);
        //$this->assertNotEquals('北京昌平回龙观;', $data['address']);

    }



    public function test_modify_user_status(){
        $userid = 3;
        $biz = new UserBiz();
        $user =  $biz->findUser($userid);
        $_SESSION['logineduser'] = $user;

        $this->assertTrue($user['status']==1);

        $biz->lockUser($userid);

        $exp = $biz->findUser($userid);
        $this->assertTrue($exp['status']==C('USER_STATUS_LOCKED'));


        $rs=$this->pdo->query('select * from cps_user_status_changed');
        $rs->setFetchMode(PDO::FETCH_ASSOC);
        $data = $rs->fetchAll();

        $this->assertTrue(count($data)==1);
        $this->assertTrue($data[0]['userid']==$userid);
        $this->assertTrue($data[0]['status']==C('USER_STATUS_LOCKED'));
        $this->assertTrue($data[0]['changedby']==$user['uname']);


    }
}
