<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 团购模块后台管理类
 * @author sunny.zeng <89781816@qq.com>
 * @since v1.0.0 团购活动基本管理
 */
class GroupBuyController extends PrivilegeController{

	/**
	 * 团购活动列表页面
	 * @return [type] [description]
	 */
	public function index()
	{
		$model 	= M('GroupJoins');
		//分页
		$count  = $model->count();// 查询满足要求的总记录数
		$Page   = new \Think\Page($count,25);
		$Page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
	    $Page->setConfig('prev', '上一页');
	    $Page->setConfig('next', '下一页');
	    $Page->setConfig('first', '首页');
	    $Page->setConfig('last', '末页');
	    $Page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
		$pageShow   = $Page->show();// 分页显示输出

		$list 	= $model->alias('a')->join('LEFT JOIN __PRODUCT__ p on p.id = a.pid')->field('a.*,p.name,p.photo_x')->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('datalist',$list);
		$this->assign('page',$pageShow);
		$this->display();
	}

	/**
	 * 团购活动信息添加页面
	 */
	public function add(){
		$products = M('product')->field('name,photo_x,id')->where(' del=0 and is_down=0')->select();
		$this->assign('products',$products);
		$this->display();
	}

	/**
	 * 团购活动信息编辑页面
	 * @return [type] [description]
	 */
	public function edit()
	{
		$id = I('get.id',0,'intval');
		$info = M('GroupJoins')->where('id='.$id)->find();
		$products = M('product')->field('name,photo_x,id')->where(' del=0 and is_down=0')->select();
		$this->assign('products',$products);
		$this->assign('detail',$info);
		$this->display();
	}

	/**
	 * 团购活动信息添加操作
	 * @return [type] [description]
	 */
	public function postAdd()
	{
		if (!IS_POST) {
			$this->error('非法请求');
		}
		$data = array(
			'pid' 			=> I('post.pid',0,'intval'),
			'group_count' 	=> I('post.group_count',0,'intval'),
			'group_price' 	=> I('post.group_price',0,'floatval'),
			'price' 		=> I('post.price',0,'floatval'),
			'starttime' 	=> strtotime(I('post.starttime')),
			'endtime' 		=> strtotime(I('post.endtime'))
		);
		$result = M('GroupJoins')->add($data);
		if ($result) {
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}

	/**
	 * 团购活动信息编辑数据提交
	 * @return [type] [description]
	 */
	public function postEdit()
	{
		if (!IS_POST) {
			$this->error('非法请求');
		}
		$id 	= I('post.id',0,'intval');
		$result = false;
		if ($id>0) {
			$data = array(
				'pid' 			=> I('post.pid',0,'intval'),
				'group_count' 	=> I('post.group_count',0,'intval'),
				'group_price' 	=> I('post.group_price',0,'floatval'),
				'price' 		=> I('post.price',0,'floatval'),
				'starttime' 	=> I('post.starttime'),
				'endtime' 		=> I('post.endtime'),
			);
			$result = M('GroupJoins')->where('id='.$id)->save($data);
		}
		if ($result) {
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}

	/**
	 * 删除操作
	 * @return [json] 删除操作结果
	 */
	public function postDel()
	{
		$id = I('post.id',0,'intval');
		if ($id>0) {
			$data = array('status' => 1);
			$result = M('GroupJoins')->where('id='.$id)->save($data);
			$this->success('操作成功');
		}else{
			$this->error('操作异常');
		}
	}

	/**
	 * 开团信息列表
	 * @return [type] [description]
	 */
	public function openlog()
	{
		$model 	= M('GroupJoinsOpen');
		$group_id = I('get.gid',0,'intval');
		if ($group_id > 0) {
			$where['group_id'] = $group_id;
		}else{
			$where = ' 1=1 ';
		}
		//分页
		$count  = $model->where($where)->count();// 查询满足要求的总记录数
		$Page   = new \Think\Page($count,25);
		$Page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
	    $Page->setConfig('prev', '上一页');
	    $Page->setConfig('next', '下一页');
	    $Page->setConfig('first', '首页');
	    $Page->setConfig('last', '末页');
	    $Page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
		$pageShow   = $Page->show();// 分页显示输出

		$list = $model->alias('a')->join('LEFT JOIN __PRODUCT__ p on p.id = a.pid')->join('LEFT JOIN __USER__ u on u.id = a.uid')->join('LEFT JOIN __GROUP_JOINS__ G on G.pid = a.pid')->field('a.*,g.starttime,g.endtime,p.name,p.photo_x,u.uname,u.photo')->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->where($where)->select();
		$this->assign('datalist',$list);
		$this->assign('page',$pageShow);
		$this->display('openlog');
	}

	/**
	 * 开团信息状态更新
	 * @return [type] [description]
	 */
	public function groupOpenStatusUpdate()
	{
		if (!IS_POST) {
			$this->error('非法请求');
		}
		$result = false;
		$id = I('post.id',0,'intval');
		$status = I('post.status',0,'intval');
		if ($id > 0) {
			$data = array('status'=> $status);
			$result = M('GroupJoinsOpen')->where('id='.$id)->save($data);
		}
		if ($result) {
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}

	/**
	 * 参团会员记录
	 * @return [type] [description]
	 */
	public function joinGroupBuyLog()
	{
		$model 	= M('GroupJoinUsers');
		//分页
		$count  = $model->count();// 查询满足要求的总记录数
		$Page   = new \Think\Page($count,25);
		$Page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
	    $Page->setConfig('prev', '上一页');
	    $Page->setConfig('next', '下一页');
	    $Page->setConfig('first', '首页');
	    $Page->setConfig('last', '末页');
	    $Page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
		$pageShow   = $Page->show();// 分页显示输出

		$list 	= $model->alias('a')->join('LEFT JOIN __PRODUCT__ p on p.id = a.pid')->join('LEFT JOIN __USER__ u on u.id = a.uid')->join('LEFT JOIN __GROUP_JOINS_OPEN__ G on G.pid = a.group_open_id')->field('a.*,p.name,p.photo_x,u.uname,u.photo')->order('pay_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('datalist',$list);
		$this->assign('page',$pageShow);
		$this->assign('admin_qx',$_SESSION['admininfo']['qx']);
		$this->display('joinuserlist');
	}
}