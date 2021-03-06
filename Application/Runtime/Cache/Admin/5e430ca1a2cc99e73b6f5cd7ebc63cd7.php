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
    <script type="text/javascript" src="/dev/Public/admin/js/mydate.js"></script>

    <title>创建团购活动</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 团购管理 <span class="c-gray en">&gt;</span> 创建活动 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <form class="form form-horizontal" action="<?php echo U('postAdd');?>" method="post" onsubmit="return submit_from();" enctype="multipart/form-data">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>活动名称：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <input type="text" class="input-text" placeholder="团购活动名称" name="name" id="name" value="">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>团购商品：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <select name="pid" class="select" id="pid">
                  <option value="0">请选择参团商品</option>
                  <?php if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($item["id"]); ?>" thumb="<?php echo ($item["photo_x"]); ?>"><?php echo ($item["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>参团人数：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <input type="text" class="input-text" placeholder="成团人数" name="group_count" id="group_count" value="">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>团购价格：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <input type="text" class="input-text" placeholder="团购价格" name="group_price" id="group_price" value="">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>普通价格：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <input type="text" class="input-text" placeholder="普通购买价格" name="price" id="price" value="">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>开始时间：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <input type="text" class="input-text" placeholder="活动开始时间" name="starttime" id="starttime" value="" onfocus="MyCalendar.SetDate(this)">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>结束时间：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <input type="text" class="input-text" placeholder="活动结束时间" name="endtime" id="endtime" value="" onfocus="MyCalendar.SetDate(this)">
            </div>
        </div>

        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                <input class="btn btn-primary radius" type="submit" name="submit" value="&nbsp;&nbsp;创建团购活动&nbsp;&nbsp;">
            </div>
        </div>
    </form>
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
    function submit_from(){
        var name=document.getElementById('name').value;
        if(name.length<1){
            alert('产品名称不能为空');
            return false;
        }
        var pid=parseInt($("#pid").val());
        if(!pid){
            alert("请选择团购商品");
            return false;
        }
        var gprice=document.getElementById('group_price').value;
        if(gprice.length<1){
            alert('产品名称不能为空');
            return false;
        }
        var price=document.getElementById('price').value;
        if(price.length<1){
            alert('团购普通价格不能为空');
            return false;
        }
        var starttime=document.getElementById('starttime').value;
        if(starttime.length<1){
            alert('活动开始时间不能为空');
            return false;
        }
        var endtime=document.getElementById('endtime').value;
        if(endtime.length<1){
            alert('活动结束时间不能为空');
            return false;
        }
    }
</script>
</body>
</html>