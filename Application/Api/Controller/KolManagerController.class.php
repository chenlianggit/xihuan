<?php
namespace Api\Controller;
use Think\Controller;

class KolManagerController extends PublicController {

	public function _initialize(){
		$this->kol = M('kol');
		$this->kol_product = M("kol_product");
	}

	public function getKolInfo() {
		$kol_id = $_GET['kol_id'];
		if ( !$kol_id ) {
			echo json_encode(array('status'=>'fail','code'=>0));
			exit();
		}
		$kol_info = $this->kol->where("id={$_GET['kol_id']}")->find();
		echo json_encode(array('status'=>'success','kol_info'=>$kol_info));
		exit();
	}

	public function getKolProductList(){
		$kol_id = $_GET['kol_id'];
		if ( !$kol_id ) {
			echo json_encode(array('status'=>'fail','code'=>0));
			exit();
		}
		$kol_product_list = $this->kol_product->where("kol_id={$_GET['kol_id']}")->select();
		foreach($kol_product_list as $k=>$v){
			$_info = $this->product_detail($v['product_id']);//M('product')->where(['id'=>$v['product_id']])->field('id,name,price_yh,photo_x')->find();
			#$_info = M('product')->where(['id'=>$v['product_id']])->field('id,name,price_yh,photo_x')->find();
			$kol_product_list[$k]['name'] = $_info['pro']['name'];
			$kol_product_list[$k]['price_yh'] = $_info['pro']['price_yh'];
			$kol_product_list[$k]['photo_x'] = $_info['pro']['photo_x'];
			$kol_product_list[$k]['endtime'] = ($v['endtime'] - time()) * 1000;
			$kol_product_list[$k]['buff'] = $_info['buff'];

		}
		echo json_encode(array('status'=>'success','kol_product_list'=>$kol_product_list));           
		exit();
	}
	public function product_detail($pro_id){
		$product=M("product");

		if (!$pro_id) {
			echo json_encode(array('status'=>0,'err'=>'商品不存在或已下架！'));
			exit();
		}

		$pro = $product->where('id='.intval($pro_id).' AND del=0 AND is_down=0')->find();
		if(!$pro){
			echo json_encode(array('status'=>0,'err'=>'商品不存在或已下架！'));
			exit();
		}

		$pro['photo_x'] ="https://xihuan.iweiji.cc/Data/".$pro['photo_x'];
		$pro['photo_d'] = "https://xihuan.iweiji.cc/Data/".$pro['photo_d'];
		$pro['brand'] = M('brand')->where('id='.intval($pro['brand_id']))->getField('name');
		$pro['cat_name'] = M('category')->where('id='.intval($pro['cid']))->getField('name');
		$pro['shouhou'] = '卖家提供售后服务';
		$pro['paytypes'] = '微信支付';
		$pro['canshulist'] = array();

		//图片轮播数组
		$img=explode(',',trim($pro['photo_string'],','));
		$b=array();
		if ($pro['photo_string']) {
			foreach ($img as $k => $v) {
				$b[] = "https://xihuan.iweiji.cc/Data/".$v;
			}
		}else{
			$b[] = $pro['photo_d'];
		}
		$pro['img_arr']=$b;//图片轮播数组

		//处理产品属性
		$b=array();$d = array();$buff=array();
		if(intval($pro['is_buff'])>0) { //如果产品属性有值才进行数据组装
			$pro_buff = M('attribute')->where('pro_id='.intval($pro_id))->field('id')->select();

			foreach ($pro_buff as $k => $value) {
				$a = M('guige')->where('pid='.intval($pro_id).' AND attr_id='.intval($value['id']))->field('id')->select();
				foreach ($a as $key => $val) {
					$b[][] = $val['id'];
				}
			}

			//组合所有规格属性
			foreach ($b[0] as $k => $v) {
				if ($b[1]) {
					foreach ($b[1] as $k1 => $v1) {
						if ($b[2]) {
							foreach ($b[2] as $k2 => $v2) {
								if ($b[3]) {
									foreach ($b[3] as $k3 => $v3) {
										$d[] = $v.','.$v1.','.$v2.','.$v3;
									}
								}else{
									$d[] = $v.','.$v1.','.$v2;
								}
							}
						}else{
							$d[] = $v.','.$v1;
						}
					}
				}else{
					$d[] = $v;
				}
			}
			//构建数组
			$buff = array();
			foreach ($d as $k => $v) {
				$valarr = explode(',', $v);
				$arrs = array();$arrsss = array();
				foreach ($valarr as $key => $val) {
					$gg = M('guige')->where('id='.intval($val))->find();
					$arrs['attrKey'] = M('attribute')->where('id='.intval($gg['attr_id']))->getField('attr_name');
					$arrs['attrId'] = intval($gg['attr_id']);
					$arrs['guigeId'] = intval($gg['id']);
					$arrs['attrValue'] = $gg['name'];
					$arrsss[] = $arrs;
				}
				$buff[$k]['priceId'] = $k;
				$buff[$k]['attrValueList'] = $arrsss;
			}
		}

		$content = str_replace('/minigucixie/Data/', "https://xihuan.iweiji.cc/Data/", $pro['content']);
		$pro['content']=html_entity_decode($content, ENT_QUOTES ,'utf-8');

		//检测产品是否收藏
		$col = M('product_sc')->where('uid='.intval($_REQUEST['uid']).' AND pid='.intval($pro_id))->getField('id');
		if ($col) {
			$pro['collect']= 1;
		}else{
			$pro['collect']= 0;
		}
		
		return array('status'=>1,'pro'=>$pro,'buff'=>$buff);
	}
}
