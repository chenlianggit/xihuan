<?php
namespace Admin\Controller;
use Think\Controller;
class StatisticsController extends PrivilegeController{

	/*
	*
	* 构造函数，用于导入外部文件和公共方法
	*/
	public function _initialize(){
		$this->order = M('Order');
		$this->order_product = M('Order_product');
		$this->user = M('User');

		$order_status = array('0'=>'已取消','10'=>'待付款','20'=>'待发货','30'=>'待收货','40'=>'已收货','50'=>'交易完成');
		$this->assign('order_status',$order_status);
	}


    /**
     * 新增用户统计功能
     */
    public function user_count(){
//查询类型 d日统计  m月统计
        $type = $_GET['type'];
        //查询商家id
        $where = '1=1';


        for($i=0;$i<12;$i++){
            //日期
            if($type=='m'){
                $day = strtotime(date('Y-m')) - 86400*30*(11-$i);
                $dayend = $day+86400*30;
                $day_String .= ',"'.date('Y/m',$day).'"';
            }else{
                $day = strtotime(date('Y-m-d')) - 86400*(11-$i);
                $dayend = $day+86400;
                $day_String .= ',"'.date('m/d',$day).'"';
            }

            //$hyxl=select('id','aaa_pts_order',"1 $where and addtime>$day and addtime<$dayend",'num');
            $hyxl = $this->user->where($where.' AND addtime>'.$day." AND addtime<".$dayend)->count('id');
            $data1.=',['.$i.','.$hyxl.']';
        }
        $this->assign('data1',$data1);
        $this->assign('day_String',$day_String);
        //当天日期的时间戳
        $today = strtotime(date('Y-m-d'));
        $this->assign('today',$today);
        $this->assign('type',$type);

        $this->display();
    }


	/*
	*
	*  订单统计功能
	*/
	public function order_count(){
		//查询类型 d日统计  m月统计
		$type = $_GET['type'];
		//查询商家id
		$where = '1=1';


		for($i=0;$i<12;$i++){
		  //日期
		  if($type=='m'){
			 $day = strtotime(date('Y-m')) - 86400*30*(11-$i);
			 $dayend = $day+86400*30;
			 $day_String .= ',"'.date('Y/m',$day).'"';
		  }else{
			 $day = strtotime(date('Y-m-d')) - 86400*(11-$i);
			 $dayend = $day+86400; 
			 $day_String .= ',"'.date('m/d',$day).'"';
		  }

		  //$hyxl=select('id','aaa_pts_order',"1 $where and addtime>$day and addtime<$dayend",'num');
		  $hyxl = $this->order->where($where.' AND addtime>'.$day." AND addtime<".$dayend)->count('id');
		  $data1.=',['.$i.','.$hyxl.']';
		}
		$this->assign('data1',$data1);
		$this->assign('day_String',$day_String);
		//当天日期的时间戳
		$today = strtotime(date('Y-m-d'));
		$this->assign('today',$today);
        $this->assign('type',$type);

		$this->display();
	}

}