<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends PublicController {
	//***************************
	//  首页数据接口
	//***************************
    public function index(){
        //如果缓存首页没有数据，那么就读取数据库
        /***********获取首页顶部轮播图************/
        $ggtop=M('guanggao')->order('sort desc,id asc')->field('id,name,photo,type,action')->limit(10)->select();
        foreach ($ggtop as $k => $v) {
            $ggtop[$k]['photo']=__DATAURL__.$v['photo'];
            $ggtop[$k]['name']=$v['name'];
            //$ggtop[$k]['name']=urlencode($v['name']);
            $ggtop[$k]['action']=urlencode($v['action']);
        }
        /***********获取首页顶部轮播图 end************/
        //======================
        //首页 获取logo
        //======================
        $logo = M('program')->where('id=1')->getField('logo');
        if ($logo) {
           $logo = __DATAURL__.$logo;
        }

        //======================
        //首页 获取
        //======================
        $info = M('web')->where('id=3')->find();
        if ($info['photo']) {
            $info['photo'] = __DATAURL__.$info['photo'];
        }

        if ($info['ename']) {
            $info['vedio'] = __DATAURL__.$info['ename'];
        } else {
            $info['vedio'] = __DATAURL__.$info['linkurl'];
        }


        //======================
        //首页 热推产品4个
        //======================
        $pro = M('indexpro')->where('state=1')->order('sort asc,addtime desc')->select();
        foreach ($pro as $k => $v) {
            $photo = M('product')->where('id='.intval($v['pro_id']))->getField('photo_d');
            $pro[$k]['cid'] = intval(M('product')->where('id='.intval($v['pro_id']))->getField('cid'));
            $pro[$k]['photo'] = __DATAURL__.$photo;
        }

    	//======================
    	//首页 推荐产品8个
    	//======================
        $prolist = M('product')->where('del=0 AND is_down=0 AND type=1')->order('sort asc,addtime desc')->field('id,name,price_yh,price,photo_x,num,is_show,is_hot,shiyong,brand_id,intro')->limit(8)->select();

    	foreach ($prolist as $k => $v) {
    		$prolist[$k]['photo_x'] = __DATAURL__.$v['photo_x'];
    	}


        $uid = intval($_REQUEST['uid']);

    	echo json_encode(array('ggtop'=>$ggtop,'logo'=>$logo,'info'=>$info,'pro'=>$pro,'prolist'=>$prolist));
    	exit();
    }

    //***************************
    //  首页产品 分页
    //***************************
    public function getlist(){
        $page = intval($_REQUEST['page']);
        if (!$page) {
            $page=2;
        }
        $limit = intval($page*8)-8;

        $pro_list = M('product')->where('del=0 AND is_down=0 AND type=1')->order('sort asc,addtime desc')->field('id,name,price_yh,price,photo_x,num,is_show,is_hot,shiyong')->limit($limit.',8')->select();
        foreach ($pro_list as $k => $v) {
            $pro_list[$k]['photo_x'] = __DATAURL__.$v['photo_x'];
        }

        echo json_encode(array('prolist'=>$pro_list));
        exit();
    }

    public function getcode(){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<32;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        echo json_encode(array('status'=>'OK','code'=>$str));
        exit();
    }

    public function test(){
        echo __DATA__;
    }

}
