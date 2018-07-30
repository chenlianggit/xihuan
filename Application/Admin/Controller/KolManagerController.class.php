<?php
namespace Admin\Controller;
use Think\Controller;
class KolManagerController extends PrivilegeController{


	public function _initialize(){
		$this->kol = M('kol');
	}

	public function index() {
			//搜索，根据广告标题搜索
			$adv_name = intval($_REQUEST['adv_name']);
			$condition = array();
			$condition['is_del']=0;
			if ($adv_name) {
				$condition['name'] = array('LIKE','%'.$adv_name.'%');
				$this->assign('name',$adv_name);
			}
	
			//分页
			$count   = $this->kol->where($condition)->count();// 查询满足要求的总记录数
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
	
			$adv_list = $this->kol->where($condition)->limit($Page->firstRow.','.$Page->listRows)->order('addtime desc')->select();		
	
			$this->assign('adv_list',$adv_list);
			$this->assign('page',$show);
		$this->display();
	}

	public function save_product() {
		$this->kol_product = M("kol_product");
		$this->kol_product->create();
		//保存数据
		if (intval($_POST['kol_product_id'])) {
			$result = $this->kol_product->where('id='.intval($_POST['kol_product_id']))->save();
		}else{
			//保存添加时间
			$this->kol_product->addtime = time();
			$result = $this->kol_product->add();
		}
		if ($result) {
			$this->success('操作成功.','index');
		}else{
			$this->error('操作失败.');
		}
	}

	public function setProduct() {
		$this->kol_product = M("kol_product");
		$productlist=M('product')->where(['del'=>0])->field('id,name')->order('addtime desc')->limit(0,999999)->select();
		$this->assign('prod_list',$productlist);
		$kol_info = $this->kol->where("id={$_GET['adv_id']}")->find();
		$kol_product_list = $this->kol_product->where("kol_id={$_GET['adv_id']}")->select();
		foreach($kol_product_list as $k=>$v){
			$_info = M('product')->where(['id'=>$v['product_id']])->field('id,name,price_yh,photo_x')->find();
			$kol_product_list[$k]['name'] = $_info['name'];
			$kol_product_list[$k]['price_yh'] = $_info['price_yh'];
			$kol_product_list[$k]['photo_x'] = $_info['photo_x'];
		}
		$this->assign('kol_info',$kol_info);
		$this->assign('kol_product_list',$kol_product_list);
		$this->display();
	}

	public function addView() {

		$this->display();
	}

	/*
	*
	* 添加或修改KOL信息
	*/
	public function save(){
		//构建数组
		/*if (!$this->guanggao->create()) {
			$this->error($this->guanggao->getError());
		}*/
		$this->kol->create();
		//上传广告图片
		if (!empty($_FILES["file"]["tmp_name"])) {
			//文件上传
			$info = $this->upload_images($_FILES["file"],array('jpg','png','jpeg'),'adv/'.date(Ymd));
		    if(!is_array($info)) {// 上传错误提示错误信息
		        $this->error($info);
		    }else{// 上传成功 获取上传文件信息
			    $this->kol->photo = 'UploadFiles/'.$info['savepath'].$info['savename'];
			    //生成国定大小的缩略图
			    /*$path_url = './Data/UploadFiles/'.$info['savepath'].$info['savename'];
			    $image = new \Think\Image();
			    $image->open($path_url);
			    $image->thumb(310, 120,\Think\Image::IMAGE_THUMB_FIXED)->save($path_url);*/
			    if (intval($_POST['adv_id'])) {
					$check_url = $this->kol->where('id='.intval($_POST['adv_id']))->getField('photo');
					$url = "Data/".$check_url;
					if (file_exists($url) && $check_url) {
						@unlink($url);
					}
				}
		    }
		}

		//保存数据
		if (intval($_POST['adv_id'])) {
			$result = $this->kol->where('id='.intval($_POST['adv_id']))->save();
		}else{
			//保存添加时间
			$this->kol->addtime = time();
			$result = $this->kol->add();
		}
		//判断数据是否更新成功
		if ($result) {
			$this->success('操作成功.','index');
		}else{
			$this->error('操作失败.');
		}
	}
}
