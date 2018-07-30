<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;

class PostController extends PrivilegeController{

	/*
	*
	* 构造函数，用于导入外部文件和公共方法
	*/
	public function _initialize(){
		$this->Post = D('Post');
	}

	/*
	*
	* 快递列表
	*/
	public function index(){
		$condition = array();
		//分页
		$count   = $this->Post->where($condition)->count();// 查询满足要求的总记录数
		$Page    = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)

		//头部描述信息，默认值 “共 %TOTAL_ROW% 条记录”
		$Page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
		//上一页描述信息
	    $Page->setConfig('prev', '上一页');
	    //下一页描述信息
	    $Page->setConfig('next', '下一页');
	    //首页描述信息
	    $Page->setConfig('first', '首页');
	    //末页描述信息
	    $Page->setConfig('last', '末页');
	    $Page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');

		$show  = $Page->show();// 分页显示输出

		$list = $this->Post->where($condition)->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display(); // 输出模板

	}


	/*
	*
	* 跳转添加或修改快递数据页面
	*/
	public function add(){
		//如果是修改，则查询对应广告信息
		if (intval($_GET['id'])) {
			$id = intval($_GET['id']);
		
			$post_info = $this->Post->where('id='.intval($id))->find();
			$this->assign('post',$post_info);
		}
		$this->display();
	}


	/*
	*
	* 添加或修物流信息
	*/
	public function save(){
		//构建数组
		$this->Post->create();
        $name = $_POST['name'];
        $price = $_POST['price'];
        $code = $_POST['code'];
        //修改对应的显示状态
        $data = array();
        $data['name'] = $name;
        $data['price'] = $price;
        $data['code'] = $code;

        Log::write("test".$name);
		//保存数据
		if (intval($_POST['id'])) {
			$result = $this->Post->where('id='.intval($_POST['id']))->save($data);
		}else{
			//保存添加时间
			$result = $this->Post->add();
		}
		//判断数据是否更新成功
		if ($result) {
			$this->success('操作成功.','index');
		}else{
			$this->error('操作失败.');
		}
	}

	/*
	*
	* 物流删除
	*/
	public function del(){
		//获取广告id，查询数据库是否有这条数据
		$id = intval($_REQUEST['did']);
		$check_info = $this->Post->where('id='.intval($id))->find();
		if (!$check_info) {
			$this->error('参数错误！');
			die();
		}

		//修改对应的显示状态

		$up = $this->Post->where('id='.intval($id))->delete();
		if ($up) {
			$this->success('操作成功.','index');
		}else{
			$this->error('操作失败.');
		}
	}

	
}