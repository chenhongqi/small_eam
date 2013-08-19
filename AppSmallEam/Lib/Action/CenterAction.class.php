<?php

//include(dirname(__FILE__) . '/../Biz/UserBiz.class.php');
Import('@.Biz.UserBiz');
Import('@.Biz.CenterBiz');

class CenterAction extends BaseAction
{

    //region----用户信息

    public function personal()
    {
        //TODO:使用AOP，进行权限判断
        if (false == $this->is_logined()) {
            $this->error('您还没登录！请首先登陆!', 'index.php?m=User&a=login');
        }

        $userid = $this->get_logined_user_id();
        $user = UserBiz::findUser($userid);
        $this->assign('user', $user);
        $this->display('personal');
    }

    public function domodify()
    {
        $safeData = UserBiz::get_safe_data_for_profile($_POST);

        $biz = new UserBiz();
        $result = $biz->modifyUser($safeData);
        switch ($result) {
            case -1:
                $this->ajaxReturn($biz->getDetailErrors(), $biz->getError(), 0);
                break;
            case -2:
                $this->ajaxReturn(array('errmsg' => $biz->getError()), '上传失败:' . $biz->getError(), 0);
                break;
            case -3:
                $this->ajaxReturn(array('errmsg' => $biz->getError()), $biz->getError(), 0);
                break;
            default:
                $this->ajaxReturn($result, 'OK', 1);
        }
    }

    //endregion

    //region----用户订单信息
    public function orders()
    {
        if (false == $this->is_logined()) {
            $this->error('您还没有登录！请首先登陆!', 'index.php?m=User&a=login');
            die();
        }

        $product = $this->get_unistu_service_product();
        $this->assign("productid", $product['id']);

        $userid = $this->get_logined_user_id();
        $orderlist = CenterBiz::get_orders_by_userid($userid);
        $this->assign("orderlist", $orderlist);

        $this->display('orders');
    }

    function get_unistu_service_product()
    {
        import("App_Unistu.Model.ProductModel");
        try {
            $dao = new ProductModel();
            return $dao->get_product_by_code(ProductModel::CPS_SERVICE_UNISTU_1);
        }
        catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function orderdetail()
    {
        $orderid = get_safe_replace(trim($_REQUEST['orderid']));
        $order = CenterBiz::findOrder($orderid);
        //$m = new Model("cps_order");
        //$order = $m->where("orderid='{$orderid}'")->find();
        $this->assign("order", $order);
        $this->assign("orderid", $orderid);

        $photolist = CenterBiz::get_order_details($orderid);
        //$m = new Model("cps_order_detail_unistu");
        //$photolist = $m->query("select * from cps_order_detail_unistu where orderid='{$orderid}'");
        $this->assign("photolist", $photolist);

        $this->display('orderdetail');

    }

    //endregion

    //region ----重置密码
    function resetpwd()
    {
        if (false == $this->is_logined()) {
            $this->error('您还没登录！请首先登陆!', 'index.php?m=User&a=login');
        }
        $this->display('resetpwd');
    }

    function doresetpwd()
    {
        $input = UserBiz::get_safe_data_for_register($_POST);
        $biz = new UserBiz();
        $result = $biz->resetPassword($input);
        switch ($result) {
            case -1:
                $this->ajaxReturn($biz->getDetailErrors(), $biz->getError(), 0);
                break;
            case -2:
                $this->ajaxReturn(array('err' => '修改密码失败!'), 'ERR:密码修改失败!', 0);
                break;
            default:
                $this->ajaxReturn(array('err' => 'OK!'), 'OK:密码修改成功!', 1);
                break;
        }
    }

    //endregion


}