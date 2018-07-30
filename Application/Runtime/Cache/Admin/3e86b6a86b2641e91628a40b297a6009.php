<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="Bookmark" href="/favicon.ico" >
    <link rel="Shortcut Icon" href="/favicon.ico" />
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

    <title>参团记录</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 团购管理 <span class="c-gray en">&gt;</span> 参团记录 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">

    <table class="table table-border table-bordered table-bg">
        <thead>

        <tr class="text-c">
            <th width="40">ID</th>
            <th width="150">商品名称</th>         
            <th width="90">团长</th>
            <th width="90">会员名称</th>
            <th width="130">购买数量（个）</th>
            <th width="80">单价（元）</th>
            <th width="80">总金额（元）</th>
            <th width="80">参团时间</th>
            <th width="80">支付状态</th>
            <th width="120">操作</th>
        </tr>
        </thead>
        <tbody id="news_option">
        <?php if(is_array($datalist)): $i = 0; $__LIST__ = $datalist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr  class="text-c">
                <td><?php echo ($item["id"]); ?></td>
                <td><img src="/dev/Data/<?php echo ($item["photo_x"]); ?>" style="width:48px;height:48px;"><br><?php echo ($item["name"]); ?></td>
                <td><?php echo ($item["uname"]); ?></td>
                <td><?php echo ($item["uname"]); ?></td>
                <td><?php echo ($item["buy_count"]); ?></td>
                <td><?php echo ($item["price"]); ?></td>
                <td><?php echo ($item["amount"]); ?></td>
                <td><?php echo (date("Y-m-d H:i:s",$item["pay_time"])); ?></td>
                <td><?php if(($item["pay_state"] == 1) ): ?>待支付
                <?php elseif($item["pay_state"] == 2): ?>已支付
                <?php else: ?> 已取消<?php endif; ?>
                </td>
                <td class="obj_1">
                    <a onclick="order_detail(<?php echo ($item["order_id"]); ?>)">支付订单</a>
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
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/dev/Public/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/dev/Public/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/dev/Public/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/dev/Public/admin/static/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer 作为公共模版分离出去-->
<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/dev/Public/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/dev/Public/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/dev/Public/admin/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript">
   function order_detail(id){
        location.href='<?php echo U("Order/show");?>?oid='+id;
    }
</script>

</body>
</html>