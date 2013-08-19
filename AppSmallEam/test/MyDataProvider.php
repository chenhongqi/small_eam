<?php

import("App_Home.Model.UserModel");
import("App_Unistu.Model.ProductModel");
import("App_Unistu.Model.AdditionalServiceModel");
import("App_Unistu.Model.AdditionalFeeModel");
import("App_Unistu.Model.OrderModel");
import("App_Unistu.Model.OrderDetailModel");
import("App_Unistu.Model.OrderAdditionalModel");
import("App_Unistu.Model.TempPhotoModel");
import("App_Unistu.Biz.OrderBiz");
include(dirname(__FILE__) . '/../../App_Unistu/Common/common.php');


class MyDataProvider
{
    public static function  student_data($i = 1)
    {
        $data = array();
        $data["recid"] = '';
        $data["studentname"] = '王大伟' . $i;
        $data["studentgender"] = '男';
        $data["studentnumber"] = '111222';
        $data["studentidnumber"] = '420123197001183372';
        $data["studentschoolname"] = '中国地质大学';
        $data["studentschoolid"] = '';
        $data["studentschoolcode"] = '0001';
        $data["studentdeptcode"] = '';
        $data["studentareacode"] = '';
        $data["studentschoolkind"] = '理工院校';
        $data["studentaddress"] = '地址地址';
        $data["studentzipcode"] = '100082';
        $data["studentmobile"] = '13426480060';
        $data["studentemail"] = 'chenhongqi@gmail.com';
        $data["studentqq"] = '99929202';

        $data["studenteducationbg"] = '博士';
        $data["studentmajor"] = '数学';
        //照片
        //$this->data["studentphoto"] = get_safe_replace(trim($post['studentphoto']));
        //状态
        $data["studentstatus"] = C('UNISTU_ORDER_DETAIL_STATUS_AUDIT_DEFAULT');

        //产品信息
        $data["productid"] = 0;
        $data["productquantity"] = 1;
        $data["supplierid"] = 0;
        $data["productcode"] = 'cps_service_unistu_1';
        $data["productname"] = '大学生图像采集-照片扫描服务';
        $data["productdiscount"] = 1;
        $data["productprice"] = 20;
        $data["productunit"] = '人';

        //金额
        $data["amount"] = '20';
        $data["moneytype"] = '元';

        $data["createtime"] = date('Y-m-d H:i:s');
        $data["updatetime"] = date('Y-m-d H:i:s');
        $data["deleted"] = 0;

        return $data;
    }

    public static function  build_empty_data()
    {
        $data = array();
        $data["recid"] = '';
        $data["studentname"] = '';
        $data["studentgender"] = '男';
        $data["studentnumber"] = '111222';
        $data["studentidnumber"] = '42012319700993993';
        $data["studentschoolname"] = '中国地质大学';
        $data["studentschoolid"] = '';
        $data["studentschoolcode"] = '0001';
        $data["studentdeptcode"] = '';
        $data["studentareacode"] = '';
        $data["studentschoolkind"] = '理工院校';
        $data["studentaddress"] = '地址地址';
        $data["studentzipcode"] = '100082';
        $data["studentmobile"] = '13426480060';
        $data["studentemail"] = 'chenhongqi@gmail.com';
        $data["studentqq"] = '99929202';

        $data["studenteducationbg"] = '博士';
        $data["studentmajor"] = '数学';
        //照片
        //$this->data["studentphoto"] = get_safe_replace(trim($post['studentphoto']));
        //状态
        //$this->data["studentstatus"] = '';

        //产品信息
        //$this->data["productid"] = get_safe_replace(trim($post['productid']));
        //$this->data["productquantity"] = get_safe_replace(trim($post['productquantity']));
        //$this->data["supplierid"] = get_safe_replace(trim($post['supplierid']));
        //$this->data["productcode"] = get_safe_replace(trim($post['productcode']));
        //$this->data["productname"] = get_safe_replace(trim($post['productname']));
        //$this->data["productdiscount"] = get_safe_replace(trim($post['productdiscount']));
        //$this->data["productprice"] = get_safe_replace(trim($post['productprice']));
        //$this->data["productunit"] = get_safe_replace(trim($post['productunit']));

        //金额
        $data["amount"] = '200';
        $data["moneytype"] = '元';

        $data["createtime"] = date('Y-m-d H:i:s');
        $data["updatetime"] = date('Y-m-d H:i:s');
        $data["deleted"] = 0;

        return $data;
    }

    public static function  Build_Main_Order_Data()
    {
        $data = array();

        $data["receivername"] = '王大伟';
        $data["receiveraddress"] = '北京宣武门大街环球财讯中心';
        $data["receivermobile"] = '13426480060';
        $data["receiverzipcode"] = '100081';
        $data["userid"] = '122';


        return $data;
    }


    public static function init_db_order_detail_sql($i)
    {
        $m = new OrderDetailModel();
        $data = MyDataProvider::student_data($i);
        $sql = build_insert_sql($m->getTableName(), $m->getDbFields(), $data);
        return $sql;
    }


    public static function init_data()
    {
        $pdo = null;
        try {
            $pdo = new PDO(DB_NAME, DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        }
        catch (PDOException $e) {
            echo "连接数据库失败：" . $e->getMessage();
        }

        $pdo->exec('delete from cps_user_status_changed');

        //----1个产品------------------------------------------
        MyDataProvider::init_product_data($pdo);
        //----2个固定费用---------------------------------------
        MyDataProvider::init_additional_service_data($pdo);
        //----3个附加服务---------------------------------------
        MyDataProvider::init_additional_fee_data($pdo);
        //----1个用户------------------------------------------
        $userid = 3;$username='chenhongqi';
        MyDataProvider::init_user_data($pdo,$userid,$username);

        //----2个订单------------------------------------------
        MyDataProvider::clear_db_order($pdo);

        $orderid1 = OrderBiz::build_order_id($userid);
        $orderid2 = OrderBiz::build_order_id($userid);
        $sql_order_2 = MyDataProvider::insert_order_sql($userid, $orderid1);
        $sql_order_3 = MyDataProvider::insert_order_sql($userid, $orderid2);
        $pdo->exec($sql_order_2);
        $pdo->exec($sql_order_3);
        //----每个订单100个明细--------------------------------
        MyDataProvider::init_order_detail_data($pdo, $orderids = array($orderid1, $orderid2));
        //----每个订单4个附加费用-------------------------------
        MyDataProvider::init_order_additional_data($pdo, $orderids = array($orderid1, $orderid2));
        //----订单金额----
        MyDataProvider::cal_order_amount($pdo,$orderids = array($orderid1, $orderid2));


        //----临时表中的10个记录--------------------------------
        MyDataProvider::init_temp_photo_data($pdo, $userid);

    }

    public static function cal_order_amount(PDO $pdo,$orderids = array()){
        //1.
        foreach($orderids as $orderid){
            $sql1 = "select sum(amount) amounts from cps_order_detail_unistu where orderid='{$orderid}'";
            $ret = $pdo->query($sql1);
            $ret->setFetchMode(PDO::FETCH_ASSOC);
            $result = $ret->fetchAll();
            $productamount =  $result[0]['amounts'];

            $sql2 = "select sum(amount) amounts from cps_order_additional where orderid='{$orderid}'";
            $ret = $pdo->query($sql2);
            $ret->setFetchMode(PDO::FETCH_ASSOC);
            $result = $ret->fetchAll();
            $additionalamount =  $result[0]['amounts'];

            $totalamount = $productamount + $additionalamount;

            $sql3 = "update cps_order set totalamount ={$totalamount},productamount={$productamount}, additionalamount ={$additionalamount} "
                ." where orderid='{$orderid}'";

            $pdo->exec($sql3);
        }
    }


    public static function init_user_data(PDO $pdo,$userid,$username)
    {
        $sql1 = MyDataProvider::clear_db_user_sql();
        $pdo->exec($sql1);

        MyDataProvider::insert_db_user($pdo,$userid, $username);
    }


    //region----临时表中的照片
    public static function init_temp_photo_data(PDO $pdo, $userid)
    {
        MyDataProvider::clear_temp_photo_data($pdo);

        for ($i = 1; $i <= 100; $i++) {
            $sql2 = MyDataProvider::build_sql_init_temp_photo($i, $userid);
            $pdo->exec($sql2);
        }

    }

    public static function clear_temp_photo_data(PDO $pdo)
    {
        $m = new TempPhotoModel();
        $sql = "delete from " . $m->getTableName();
        $pdo->exec($sql);
    }

    public static function build_sql_init_temp_photo($i, $userid)
    {
        $m = new TempPhotoModel();
        $data = MyDataProvider::temp_photo_data($i, $userid);
        $sql = build_insert_sql($m->getTableName(), $m->getDbFields(), $data);
        return $sql;
    }

    public static function temp_photo_data($i, $userid)
    {
        $data = array();
        $data["recid"] = $i;
        $data["userid"] = $userid;
        $data["studentname"] = '王大伟' . $i;
        $data["studentgender"] = '男';
        $data["studentnumber"] = '111222';

        $data["studentschoolname"] = '中国地质大学';
        $data["studentschoolid"] = '';
        $data["studentschoolcode"] = '0001';
        $data["studentdeptcode"] = '';
        $data["studentareacode"] = '';
        $data["studentschoolkind"] = '理工院校';
        $data["studentaddress"] = '地址地址';
        $data["studentzipcode"] = '100082';
        $data["studentmobile"] = '13426480060';
        $data["studentemail"] = 'chenhongqi@gmail.com';
        $data["studentqq"] = '99929202';

        //唯一
        $data["studentidnumber"] = '420123199001183372';
        $data["studenteducationbg"] = '博士'.rand(100,1000);
        $data["studentmajor"] = '数学:'.rand(100,1000);

        //照片
        $data["studentphoto"] = '/img0/unistu/2013/7/18/r2/51e76cc6066c41.69332264.jpg';
        $data["studentphotothumb"] = '/img0/unistu/2013/7/18/r2/51e76cc6066c41.69332264_thumb.jpg';
        //状态
        $data["studentstatus"] = C('UNISTU_ORDER_DETAIL_STATUS_AUDIT_DEFAULT');
        //产品信息
        $data["productid"] = 0;
        $data["productquantity"] = 1;
        $data["supplierid"] = 0;
        $data["productcode"] = 'cps_service_unistu_1';
        $data["productname"] = '大学生图像采集-照片扫描服务';
        $data["productdiscount"] = 1;
        $data["productprice"] = 20;
        $data["productunit"] = '人';

        //金额
        $data["amount"] = '20';
        $data["moneytype"] = '元';

        $data["createtime"] = date('Y-m-d H:i:s');
        $data["updatetime"] = date('Y-m-d H:i:s');
        $data["deleted"] = 0;

        return $data;
    }

    //endregion

    //region----产品
    public static function init_product_data(PDO $pdo)
    {
        MyDataProvider::clear_product_data($pdo);
        MyDataProvider::insert_product_data($pdo);
    }

    public static function clear_product_data(PDO $pdo)
    {
        $m = new ProductModel();
        $sql = "delete from " . $m->getTableName();
        $pdo->exec($sql);
    }

    public static function insert_product_data(PDO $pdo)
    {
        $m = new ProductModel();
        $data = MyDataProvider::product_data();
        $sql = build_insert_sql($m->getTableName(), $m->getDbFields(), $data);
        $pdo->exec($sql);
    }

    public static function product_data()
    {
        $data = array();
        $data["id"] = 0;
        $data["supplierid"] = 0;
        $data["suppliername"] = '中国图片社';
        $data["code"] = 'cps_service_unistu_1';
        $data["name"] = '大学生图像采集-照片扫描服务';
        $data["discount"] = 1;
        $data["price"] = 20;
        $data["unit"] = '人';
        $data["moneytype"] = '元';
        $data["freepost"] = 0;
        return $data;
    }


    //endregion

    //region----订单明细
    static function init_order_detail_data(PDO $pdo, $orderids = array())
    {
        MyDataProvider::clear_order_detail_data($pdo);

        foreach ($orderids as $value) {
            for ($i = 1; $i <= 100; $i++) {
                $sql2 = MyDataProvider::build_sql_init_order_detail($value);
                $pdo->exec($sql2);
            }
        }
    }

    public static function clear_order_detail_data(PDO $pdo)
    {
        $m = new OrderDetailModel();
        $sql = "delete from " . $m->getTableName();
        $pdo->exec($sql);
    }

    public static function build_sql_init_order_detail($orderid)
    {
        $m = new OrderDetailModel();
        $data = MyDataProvider::order_detail_data($orderid);
        $sql = build_insert_sql($m->getTableName(), $m->getDbFields(), $data);
        return $sql;
    }

    public static function order_detail_data($orderid)
    {
        $data = array();
        //$data["recid"]='';
        $data["orderid"] = $orderid;
        $data["studentname"] = '王大伟'.rand(1,1000);
        $data["studentgender"] = '男';
        $data["studentnumber"] = '90122';

        $data["studentschoolname"] = '北京大学';
        $data["studentschoolid"] = '001';
        $data["studentschoolcode"] = '001';
        $data["studentdeptcode"] = '002';
        $data["studentareacode"] = '003';
        $data["studentschoolkind"] = '普通院校';
        $data["studentaddress"] = '北京昌平沙河203';
        $data["studentzipcode"] = '100288';
        $data["studentmobile"] = '13458588989';
        $data["studentemail"] = 'chenhongqi@sina.com';
        $data["studentqq"] = '';
        $data["studentphoto"] = '/img0/unistu/2013/7/29/r2/0990.jpg';
        $data["studentphotothumb"] = '/img0/unistu/2013/7/29/r2/0990_thumb.jpg';
        $data["studentstatus"] = C('UNISTU_ORDER_DETAIL_STATUS_AUDIT_DEFAULT');

        //唯一
        $data["studentidnumber"] = '420123199001183372';
        $data["studenteducationbg"] = '学历'.rand(100,1000);
        $data["studentmajor"] = '专业:'.rand(100,1000);

        $data["productid"] = 0;
        $data["productquantity"] = 1;
        $data["supplierid"] = 0;
        $data["productcode"] = 'cps_service_unistu_1';
        $data["productname"] = '大学生图像采集-照片扫描服务';
        $data["productdiscount"] = 1;
        $data["productprice"] = 20;
        $data["productunit"] = '人';

        $data["moneytype"] = '元';
        $data["amount"] = '20';
        $data["createtime"] = date("Y-m-d H:i:s");
        $data["updatetime"] = date("Y-m-d H:i:s");
        $data["message"] = '';
        $data["unistuproduct"] = '';

        return $data;
    }


    //endregion

    //region ----主订单测试数据
    public static function clear_db_order(PDO $pdo)
    {
        $m = new OrderModel();
        $sql = "delete from " . $m->getTableName();
        $pdo->exec($sql);
    }

    public static function insert_order_sql($userid,$orderid)
    {
        $m = new OrderModel();
        $data = MyDataProvider::order_data($userid,$orderid);
        $sql = build_insert_sql($m->getTableName(), $m->getDbFields(), $data);
        return $sql;
    }

    public static function order_data($userid,$orderid)
    {
        $data = array();

        $data["userid"] = $userid;
        $data["orderid"] = $orderid;

        $data["receivername"] = '王大伟';
        $data["receiveraddress"] = '北京宣武门大街环球财讯中心';
        $data["receivermobile"] = '13426480060';
        $data["receiverzipcode"] = '100081';
        $data["orderfor"] = 'unistu';
        $data["orderkind"] = 'buy';
        $data["orderstatus"] = 'beforeauditing';
        $data["createtime"] = date("Y-m-d H:i:s");
        $data["updatetime"] = date("Y-m-d H:i:s");
        //$data["totalamount"] =
        //$data["productamount"] =
        //$data["additionalamount"] =
        $data["payway"] = 'UPOP';
        $data["paystatus"] = 0;
        //$data["paytime"] =
        $data["memo"] = '测试记录';
        $data["discount"] = 1;
        //$data["realamount"] =
        $data["moneytype"] = '元';
        //$data["qid"] =
        //$data["ordertime"] =

        return $data;
    }

    //endregion

    //region----用户数据
    public static function clear_db_user_sql()
    {
        $m = new UserModel();
        $sql = "delete from " . $m->getTableName();
        return $sql;
    }

    public static function insert_db_user(PDO $pdo, $uid, $uname)
    {
        $m = new UserModel();
        $data = MyDataProvider::user_info($uid, $uname);
        $sql = build_insert_sql($m->getTableName(), $m->getDbFields(), $data);
        $pdo->exec($sql);
    }

    public static function user_info($uid, $uname)
    {
        $data = array();
        $data["uid"] = $uid;
        $data["uname"] = $uname;
        $data["upw"] = 'c4ca4238a0b923820dcc509a6f75849b';
        $data["uemail"] = 'chenhongqi@sina.com';
        $data["uIDtype"] = '身份证';
        $data["uIDno"] = '';
        $data["ubirthday"] = '';
        $data["utname"] = '陈红旗';
        $data["tel1"] = '';
        $data["tel2"] = '';
        $data["usex"] = '';
        $data["uqq"] = '';
        $data["umsn"] = '';
        $data["remark"] = '';
        $data["address"] = '';
        $data["zcode"] = '';
        $data["createdate"] = date('Y-m-d H:i:s');
        $data["alterdate"] = date('Y-m-d H:i:s');
        $data["status"] = '1';
        $data["usertype"] = '';
        $data["usergrade"] = '';
        $data["hobby"] = '';
        $data["job"] = '';
        $data["about"] = '';
        $data["degree"] = '';
        $data["avatar"] = '';

        return $data;

    }

    //endregion


    //region----附加费用数据
    static function init_order_additional_data(PDO $pdo, $orderids = array())
    {
        //1.清空
        $sql1 = MyDataProvider::clear_order_additional_sql();
        $pdo->exec($sql1);

        foreach($orderids as $orderid){
        //2.添加附加固定费用
        MyDataProvider::insert_order_additional_fee_data($pdo,$orderid);

        //3.添加附加服务费
        MyDataProvider::insert_order_additional_service_data($pdo,$orderid);
        }
    }

    static function clear_order_additional_sql()
    {
        $m = new OrderAdditionalModel();
        $sql = "delete from " . $m->getTableName();
        return $sql;
    }

    static function insert_order_additional_fee_data(PDO $pdo,$orderid)
    {
        $data = MyDataProvider::get_additional_fee_data($pdo);

        $m = new OrderAdditionalModel();
        foreach ($data as $value) {
            $feeData = OrderAdditionalModel::MappingFeeData($value);
            $feeData['orderid']=$orderid;
            $sql = build_insert_sql($m->getTableName(), $m->getDbFields(), $feeData);
            $pdo->exec($sql);
        }
    }

    static function insert_order_additional_service_data(PDO $pdo,$orderid)
    {
        $data = MyDataProvider::get_additional_service_data($pdo);

        $m = new OrderAdditionalModel();
        foreach ($data as $value) {
            $feeData = OrderAdditionalModel::MappingServiceData($value);
            $feeData['orderid']=$orderid;
            $sql = build_insert_sql($m->getTableName(), $m->getDbFields(), $feeData);
            $pdo->exec($sql);
        }
    }

    static function get_additional_fee_data(PDO $pdo)
    {
        $m = new AdditionalFeeModel();
        $table = $m->getTableName();
        $rs = $pdo->query("SELECT * FROM {$table}");
        $rs->setFetchMode(PDO::FETCH_ASSOC);
        $data = $rs->fetchAll();
        return $data;
    }

    static function get_additional_service_data(PDO $pdo)
    {
        $m = new AdditionalServiceModel();
        $table = $m->getTableName();
        $rs = $pdo->query("SELECT * FROM {$table}");
        $rs->setFetchMode(PDO::FETCH_ASSOC);
        $data = $rs->fetchAll();
        return $data;
    }

    //endregion

    //region----附加固定费用
    static function init_additional_fee_data(PDO $pdo)
    {
        $sql1 = MyDataProvider::clear_additional_fee_sql();
        $pdo->exec($sql1);

        $sql1 = MyDataProvider::init_additional_fee_sql(MyDataProvider::init_additional_fee_1());
        $sql2 = MyDataProvider::init_additional_fee_sql(MyDataProvider::init_additional_fee_2());
        $pdo->exec($sql1);
        $pdo->exec($sql2);
    }

    static function init_additional_fee_sql($data)
    {
        $m = new AdditionalFeeModel();
        $sql = build_insert_sql($m->getTableName(), $m->getDbFields(), $data);
        return $sql;
    }

    public static function init_additional_fee_1()
    {
        $data = array();
        $data["feeid"] = '1';
        $data["feename"] = '包装费';
        $data["orderfor"] = 'unistu';
        $data["amount"] = '20';
        $data["moneytype"] = '元';
        $data["createtime"] = date('Y-m-d H:i:s');
        $data["updatetime"] = date('Y-m-d H:i:s');
        $data["deleted"] = '0';
        return $data;
    }

    public static function init_additional_fee_2()
    {
        $data = array();
        $data["feeid"] = '2';
        $data["feename"] = '邮寄费';
        $data["orderfor"] = 'unistu';
        $data["amount"] = '20';
        $data["moneytype"] = '元';
        $data["createtime"] = date('Y-m-d H:i:s');
        $data["updatetime"] = date('Y-m-d H:i:s');
        $data["deleted"] = '0';
        return $data;
    }

    static function clear_additional_fee_sql()
    {
        $m = new AdditionalFeeModel();
        $sql = "delete from " . $m->getTableName();
        return $sql;
    }

    //endregion

    //region----附加服务
    static function init_additional_service_data(PDO $pdo)
    {
        $sql1 = MyDataProvider::clear_additional_service_sql();
        $pdo->exec($sql1);

        $sql1 = MyDataProvider::init_additional_service_sql(MyDataProvider::init_additional_service_1());
        $sql2 = MyDataProvider::init_additional_service_sql(MyDataProvider::init_additional_service_2());
        $sql3 = MyDataProvider::init_additional_service_sql(MyDataProvider::init_additional_service_3());
        $pdo->exec($sql1);
        $pdo->exec($sql2);
        $pdo->exec($sql3);

    }

    static function clear_additional_service_sql()
    {
        $m = new AdditionalServiceModel();
        $sql = "delete from " . $m->getTableName();
        return $sql;
    }

    static function init_additional_service_sql($data)
    {
        $m = new AdditionalServiceModel();
        $sql = build_insert_sql($m->getTableName(), $m->getDbFields(), $data);
        return $sql;
    }

    public static function init_additional_service_1()
    {
        $data = array();
        $data["feeid"] = '1';
        $data["feename"] = '光盘制作费';
        $data["orderfor"] = 'unistu';
        $data["amount"] = '20';
        $data["moneytype"] = '元';
        $data["createtime"] = date('Y-m-d H:i:s');
        $data["updatetime"] = date('Y-m-d H:i:s');
        $data["deleted"] = '0';
        $data["free"] = '0';
        return $data;
    }

    public static function init_additional_service_2()
    {
        $data = array();
        $data["feeid"] = '2';
        $data["feename"] = '短信通知费';
        $data["orderfor"] = 'unistu';
        $data["amount"] = '20';
        $data["moneytype"] = '元';
        $data["createtime"] = date('Y-m-d H:i:s');
        $data["updatetime"] = date('Y-m-d H:i:s');
        $data["deleted"] = '0';
        $data["free"] = '0';
        return $data;
    }

    public static function init_additional_service_3()
    {
        $data = array();
        $data["feeid"] = '3';
        $data["feename"] = '服务3费';
        $data["orderfor"] = 'unistu';
        $data["amount"] = '20';
        $data["moneytype"] = '元';
        $data["createtime"] = date('Y-m-d H:i:s');
        $data["updatetime"] = date('Y-m-d H:i:s');
        $data["deleted"] = '0';
        $data["free"] = '1';
        return $data;
    }
    //endregion

}