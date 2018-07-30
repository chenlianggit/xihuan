<?php
namespace Admin\Controller;
use Think\Controller;
class CommentController extends PrivilegeController{

	/*
	*
	* 构造函数，用于导入外部文件和公共方法
	*/
	public function _initialize(){
		$this->product_dp = M('product_dp');
	}


	/*
	*
	* 获取所有产品评价
	*/
	public function index(){
        $condition = array();

        //分页
        $count   = $this->product_dp->where($condition)->count();// 查询满足要求的总记录数
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

        $list = $this->product_dp->table('tp_product_dp dp, tp_order_product od, tp_user user')->where('dp.order_id = od.id and dp.uid = user.id')->
        field('dp.id as id,dp.uid as uid,dp.pid as pid,dp.order_id as order_id,dp.num as num,dp.concent as concent,
        dp.addtime as addtime,dp.audit as audit,dp.reply_content as reply_content,dp.reply_time as reply_time,od.name as name,user.name as username')->limit($Page->firstRow.','.$Page->listRows)->select();
        //$list = $this->product_dp->where($condition)->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display(); // 输出模板

	}

	/**
     * 回复用户评价
     */
	public function reply(){
	    $id = $_REQUEST['id'];
	    
	    $result = $this->product_dp->where('id='.$id)->find();
        $this->assign('dp',$result);
        $this->assign('id',$id);
        $this->display();
    }

    /**
     * 保存对用户评价的回复
     */
    public function save(){
        $id = $_POST['id'];
        $reply_content = $_POST['reply_content'];

        $data['id'] = $id;
        $data['reply_content'] = $reply_content;
        $data['reply_time']=time();

        $result = $this->product_dp->where('id='.$id)->save($data);

        //判断数据是否更新成功
        if ($result) {
            $this->success('操作成功.','index');
        }else{
            $this->error('操作失败.');
        }
    }


    /**
     * 修改审核状态
     */
    public function edit_status(){
        $id = intval($_REQUEST['id']);

        $result = $this->product_dp->where('id='.$id)->find();

        if (!$result) {
            $this->error('评价不存在！');
            exit();
        }
        $data = array();
        $data['audit'] = $result['audit']==1 ? 0 : 1;
        $up = M('product_dp')->where('id='.intval($id))->save($data);
        if ($up) {
            $this->redirect('index',array('page'=>intval($_REQUEST['page'])));
            exit();
        }else{
            $this->error('操作失败！');
            exit();
        }
    }
}






































