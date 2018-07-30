<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/dev/Public/admin/lib/html5shiv.js"></script>
    <script type="text/javascript" src="/dev/Public/admin/lib/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/dev/Public/admin/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/dev/Public/admin/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/dev/Public/admin/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/dev/Public/admin/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="/dev/Public/admin/static/h-ui.admin/css/style.css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="/dev/Public/admin/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <script type="text/javascript" src="/dev/Public/admin/js/jquery.js"></script>
    <script type="text/javascript" src="/dev/Public/admin/js/action.js"></script>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 新闻管理 <span class="c-gray en">&gt;</span> 全部新闻 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">

    <!--<div class="text-c">-->
        <!--<form name='form' action="<?php echo U('index','index');?>" method='get'>-->
        <!--<input type="text" class="input-text" style="width:250px" placeholder="标题" name='news_name' id="name" value="<?php echo $name;?>">-->

                <!--新闻类别：-->
                <!--<select class="inp_1 inp_6" name="pid" id="pid">-->
                    <!--<option value="">全部类别</option>-->
                    <!--<?php if(is_array($cate_list)): $i = 0; $__LIST__ = $cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i;?>-->
                        <!--<?php if($cate["id"] == $cid): ?>-->
                            <!--<option value="<?php echo ($cate["id"]); ?>" selected="selected" >- <?php echo ($cate["name"]); ?></option>-->
                            <!--<?php else: ?>-->
                            <!--<option value="<?php echo ($cate["id"]); ?>" >- <?php echo ($cate["name"]); ?></option>-->
                        <!--<?php endif; ?>-->
                    <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                <!--</select>-->

        <!--<button type="button" class="btn btn-success" id="" name="" onclick="product_option();"><i class="Hui-iconfont">&#xe665;</i> 搜索</button>-->
        <!--</form>-->
    <!--</div>-->
    <br>

    <table class="table table-border table-bordered table-bg">
        <thead>

        <tr class="text-c">
            <th width="40">ID</th>
            <th width="150">新闻标题</th>
            <th width="100">所属类别</th>
            <th width="130">操作</th>
        </tr>
        </thead>


        <tbody id="news_option">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr  class="text-c">
                <td><?php echo ($vo["id"]); ?></td>
                <td><?php echo ($vo["name"]); ?></td>
                <td><?php echo ($vo["c_name"]); ?></td>
                <td><a href="<?php echo U('add');?>?news_id=<?php echo ($vo["id"]); ?>">修改</a> |
                    <!-- <a href="<?php echo U('review');?>?news_id=<?php echo ($vo["id"]); ?>">查看评论</a> |  -->
                    <a onclick="del_id_url2(<?php echo ($vo["id"]); ?>)">删除
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>

        <tr class="text-c">
            <td colspan="10" class="td_2">
                <?php echo ($page); ?>
            </td>
        </tr>
    </table>
</div>




<script>
function product_option(){
      $('form').submit();
}

function del_id_url2(id){
   if(confirm("确认删除吗？"))
   {
	  location='<?php echo U("del");?>?did='+id;
   }
}
</script>
</body>
</html>