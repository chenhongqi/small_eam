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

    public function test_order_by()
    {
      $m=M('cps_order_detail_unistu');
        $data = $m->field('recid,studentname')->order('studentname desc')->select();
        foreach($data as $key=>$value){
            print $value['studentname'].'\n';
        }
    }



}