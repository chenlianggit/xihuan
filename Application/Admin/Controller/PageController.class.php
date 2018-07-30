<?php
namespace Admin\Controller;
use Think\Controller;
class PageController extends PrivilegeController{
	public function adminindex(){	
		$this->display();
	}
	public function shopindex(){	
		$this->display();
	}		
}