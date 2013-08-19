<?php

class CenterBiz
{
    protected $_error =   '';
    public function getError(){
        return $this->_error;
    }


    public static function findOrder($orderid)
    {
        import("App_Unistu.Model.OrderModel");
        //$dao = D('App_Unistu://Order');
        //$dao = D('Order');
        $dao = new OrderModel();
        $order = $dao->find($orderid);
        if(false == $order){
            $err = $dao->getError()==''?$dao->getDbError():$dao->getError();
            Log::write($err,Log::ERR);
        }
        return $order;
    }


    public static function get_order_details($orderid)
    {
        import("App_Unistu.Model.OrderDetailModel");
        //$dao = D('App_Unistu://OrderDetail');
        //$dao = D('OrderDetail');
        //$photolist = $dao->query("select * from cps_order_detail_unistu where orderid='{$orderid}'");
        $dao = new OrderDetailModel();
        $photolist = $dao->where("orderid='{$orderid}'")->select();
        if(false == $photolist){
            $err = $dao->getError()==''?$dao->getDbError():$dao->getError();
            Log::write($err,Log::ERR);
        }
        return $photolist;
    }



    public static function get_orders_by_userid($userid)
    {
        import("App_Unistu.Model.OrderModel");
        //$dao = D('Order');
        $dao = new OrderModel();
        //$orderlist = $dao->query("select * from cps_order where userid={$userid}");
        $orderlist = $dao->where("userid='{$userid}'")->select();
        if(false == $orderlist){
            $err = $dao->getError()==''?$dao->getDbError():$dao->getError();
            Log::write($err,Log::ERR);
        }
        return $orderlist;
    }


}