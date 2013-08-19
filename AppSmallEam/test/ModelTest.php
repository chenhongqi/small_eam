<?php
require_once 'base.php';
//require '../Lib/Model/OrderModel.class.php';
require_once 'MyDataProvider.php';
//require_once 'Functions.php';

class ModelTest extends PHPUnit_Framework_TestCase
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

}
