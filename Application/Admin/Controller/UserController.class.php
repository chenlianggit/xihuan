<?php
namespace Admin\Controller;
/**
 *
 * @category   UserController
 * @package    User
 * @author  stone <shijiangbo929@163.com>
 * @license
 * @version    PHP version 5.4
 * @link
 * @since   2016年12月7日
 *
 **/
class UserController extends PrivilegeController{
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
     * 普通会员的管理
     */
    public function index(){
        $aaa_pts_qx=1;
        $type=$_GET['type'];
        $id=(int)$_GET['id'];
        $tel = trim($_REQUEST['tel']);
        $name = trim($_REQUEST['name']);

        $names=$this->htmlentities_u8($_GET['name']);
        //搜索
        $where="1=1";
        $name!='' ? $where.=" and name like '%$name%'" : null;
        $tel!='' ? $where.=" and tel like '%$tel%'" : null;

        define('rows',20);
        $count=M('user')->where($where)->count();
        $rows=ceil($count/rows);

        $page=(int)$_GET['page'];
        $page<0?$page=0:'';
        $limit=$page*rows;
        $userlist=M('user')->where($where)->order('id desc')->limit($limit,rows)->select();
        $page_index=$this->page_index($count,$rows,$page);
        foreach ($userlist as $k => $v) {
            $userlist[$k]['addtime']=date("Y-m-d H:i",$v['addtime']);
        }
        //====================
        // 将GET到的参数输出
        //=====================
        $this->assign('name',$name);
        $this->assign('tel',$tel);


        //=============
        //将变量输出
        //=============
        $this->assign('page_index',$page_index);
        $this->assign('page',$page);
        $this->assign('userlist',$userlist);
        $this->display();
    }

    public function userList(){
        $aaa_pts_qx=1;
        $type=$_GET['type'];
        $id=(int)$_GET['id'];
        $tel = trim($_REQUEST['tel']);
        $name = trim($_REQUEST['name']);

        $names=$this->htmlentities_u8($_GET['name']);
        //搜索
        $where="1=1";
        $name!='' ? $where.=" and name like '%$name%'" : null;
        $tel!='' ? $where.=" and tel like '%$tel%'" : null;

        define('rows',5);
        $count=M('user')->where($where)->count();
        $rows=ceil($count/rows);

        $page=(int)$_GET['page'];
        $page<0?$page=0:'';
        $limit=$page*rows;
        $userlist=M('user')->where($where)->order('id desc')->limit($limit,rows)->select();
        $page_index=$this->page_index($count,$rows,$page);
        foreach ($userlist as $k => $v) {
            $userlist[$k]['addtime']=date("Y-m-d H:i",$v['addtime']);
        }
        //====================
        // 将GET到的参数输出
        //=====================
        $this->assign('name',$name);
        $this->assign('tel',$tel);


        //=============
        //将变量输出
        //=============
        $this->assign('page_index',$page_index);
        $this->assign('page',$page);
        $this->assign('userlist',$userlist);
        $this->display();
    }

    //*************************
    //会员地址管理
    //*************************
    public function address(){
        // $aaa_pts_qx=1;
        $id=(int)$_GET['id'];
        if($id<1){return;}
        if($_GET['type']=='del' && $id>0 && $_SESSION['admininfo']['qx']==4){
            $this->delete('address',$id);
        }
        //搜索
        $address=M('address')->where("uid=$id")->select();

        //=============
        //将变量输出
        //=============
        $this->assign('address',$address);
        $this->display();
    }

    /**
     * 删除用户
     */
    public function del()
    {
        $id = intval($_REQUEST['did']);
        $info = M('user')->where('id='.intval($id))->find();
        if (!$info) {
            $this->error('会员信息错误.'.__LINE__);
            exit();
        }

        $data=array();
        $data['del'] = $info['del'] == '1' ?  0 : 1;
        $up = M('user')->where('id='.intval($id))->save($data);
        if ($up) {
            $this->redirect('User/index',array('page'=>intval($_REQUEST['page'])));
            exit();
        }else{
            $this->error('操作失败.');
            exit();
        }
    }


    /*
	*
	* 获取、查询所有订单数据
	*/
    public function userOrders(){

        $id=(int)$_GET['id'];
        $page=(int)$_GET['page'];

        $pay_type = trim($_REQUEST['pay_type']);//支付类型
        $pay_status = $_REQUEST['pay_status']; //订单状态
        $start_time = intval(strtotime($_REQUEST['start_time'])); //订单状态
        $end_time = intval(strtotime($_REQUEST['end_time'])); //订单状态
        //构建搜索条件
        $condition = array();
        $condition['del'] = 0;
        $where = '1=1 AND del=0'.' AND uid='.$id;
        //根据支付类型搜索
        if ($pay_type) {
            $condition['type'] = $pay_type;
            $where .=' AND type='.$pay_type;
            //搜索内容输出
            $this->assign('pay_type',$pay_type);
        }
        //根据订单状态搜索
        if ($pay_status!='') {
            if ($pay_status<10 && intval($pay_statusar)>0) {
                //小于10的为退款
                $condition['back'] = $pay_status;
                $where .=' AND back='.intval($pay_status);
            }else{
                //大于10的为正常订单
                $condition['status'] = $pay_status;
                $where .=' AND status='.intval($pay_status);
            }

            //搜索内容输出
            $this->assign('pay_status',$pay_status);
        }
        //根据下单时间搜索
        if ($start_time) {
            $condition['addtime'] = array('gt',$start_time);
            $where .=' AND addtime>'.$start_time;
            //搜索内容输出
            $this->assign('start_time',date("Y-m-d",$start_time));
        }
        //根据下单时间搜索
        if ($end_time) {
            $condition['addtime'] = array('lt',$end_time);
            $where .=' AND addtime<'.$end_time;
            //搜索内容输出
            $this->assign('end_time',date("Y-m-d",$end_time));
        }
        /*if ($start_time && $end_time) {
            $condition['addtime'] = array('eq','addtime>'.$start_time.' AND addtime<='.$end_time);
        }*/

        //分页
        $count   = $this->order->where($where)->count();// 查询满足要求的总记录数
        $Page    = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        //分页跳转的时候保证查询条件
        foreach($condition as $key=>$val) {
            $Page->parameter[$key]  =  urlencode($val);
        }
        if ($start_time && $end_time) {
            $addtime = 'addtime>'.$start_time.' AND addtime<'.$end_time;
            $Page->parameter['addtime']  =  urlencode($addtime);
        }

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
        /*
        * 分页主题描述信息
        * %FIRST%  表示第一页的链接显示
        * %UP_PAGE%  表示上一页的链接显示
        * %LINK_PAGE%  表示分页的链接显示
        * %DOWN_PAGE% 	表示下一页的链接显示
        * %END%   表示最后一页的链接显示
        */
        $Page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');

        $show    = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $order_list = $this->order->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($order_list as $k => $v) {
            $order_list[$k]['u_name'] = M('user')->where('id='.intval($v['uid']))->getField('name');
        }
        //echo $where;

        $this->assign('order_list',$order_list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板

    }


    /*
	*
	* 查看订单详情
	*/
    public function show(){
        //获取传递过来的id
        $order_id = intval($_GET['oid']);
        if(!$order_id) {
            $this->error('系统错误.');
        }

        //根据订单id获取订单数据还有商品信息
        $order_info = $this->order->where('id='.intval($order_id))->find();
        $order_pro = $this->order_product->where('order_id='.intval($order_id))->select();
        if (!$order_info || !$order_pro) {
            $this->error('订单信息错误.');
            exit();
        }

        $post_info = array();
        if (intval($order_info['post'])) {
            $post_info = M('post')->where('id='.intval($order_info['post']))->find();
        }

        $this->assign('post_info',$post_info);
        $this->assign('order_info',$order_info);
        $this->assign('order_pro',$order_pro);
        $this->display();
    }
}