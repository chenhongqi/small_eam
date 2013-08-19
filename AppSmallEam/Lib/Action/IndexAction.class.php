<?php

class IndexAction extends BaseAction {

    public function index() {

        $this->assign('AppName',APP_NAME);
        $this->display('index');
    }

    public function test(){
        $this->assign('waitSecond',3);
        //$this->success('测试成功');
        $this->error("测试失败提示<br>sfjdsjflsdjf<br>fksdfsd<br>fksdfsd<br>fksdfsd<br>fksdfsd");
    }

}