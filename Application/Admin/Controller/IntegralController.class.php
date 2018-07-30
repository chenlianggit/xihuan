<?php
namespace Admin\Controller;
use Think\Controller;
class IntegralController  extends Controller {
     public function _initialize(){
	$this->settings = M("settings");
     }
    public function index() {
        //$this->assign('current',1);
        $this->display();
    }

    public function Settings() {
	$auth = $this->settings->where('skey="integral_auth"')->find();
	$share_area = $this->settings->where('skey="integral_share_area"')->find();
	$rmb_ratio = $this->settings->where('skey="integral_rmb_ratio"')->find();

	$this->view->assign('integral_auth',$auth);
	$this->view->assign('integral_share_area',$share_area);
	$this->view->assign('integral_rmb_ratio',$rmb_ratio);

        $this->display();
    }

    public function update() {
	unset($_POST['submit']);
	foreach($_POST as $key=>$val) {
		$this->settings->where("skey='{$key}'")->save(['sval'=>$val]);
	}
	$this->success('操作成功！');
    }
}
