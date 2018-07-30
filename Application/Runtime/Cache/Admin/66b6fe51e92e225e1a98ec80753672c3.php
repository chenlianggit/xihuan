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
    <script type="text/javascript" src="/Public/admin/lib/html5shiv.js"></script>
    <script type="text/javascript" src="/Public/admin/lib/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui.admin/css/style.css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="/Public/admin/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <script type="text/javascript" src="/Public/admin/js/jquery.js"></script>
    <script type="text/javascript" src="/Public/admin/js/action.js"></script>

    <title>KOL管理</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> Kol管理 <span class="c-gray en">&gt;</span> 全部Kol <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <div class="text-c">
        <form class="form form-horizontal" action="<?php echo U('save_product');?>" method="post" onsubmit="return ac_from();" enctype="multipart/form-data">
        <select class="inp_1 input-text" id="product_item" name="product_id" style="width:auto;">
            <?php if(is_array($prod_list)): $i = 0; $__LIST__ = $prod_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($item["id"]); ?>"><?php echo ($item['name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
        <input type="text" class="input-text" style="width:250px" placeholder="KOL价格" id="price"  name="price" />
        <input type="text" class="input-text" style="width:250px" placeholder="数量" id="num" name="num"/>
        <input type="hidden" name="kol_id" id="kol_id" value="<?php echo $kol_info['id']; ?>">
        <button class="btn btn-primary radius" type="submit" name="submit"><i class="Hui-iconfont">&#xe665;</i> 添加</button>
        </form>
    </div>
    <br>
    <table class="table table-border table-bordered table-bg">
        <thead>

        <tr class="text-c">
            <th width="20">ID</th>
            <th width="100">图片</th>
            <th width="180">产品名称</th>
            <th width="40">平台价格/元</th>
            <th width="40">KOL价格/元</th>
            <th width="40">数量</th>
        </tr>
        </thead>


        <tbody id="news_option">
        <?php if(is_array($kol_product_list)): $i = 0; $__LIST__ = $kol_product_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$adv): $mod = ($i % 2 );++$i;?><tr class="text-c">
                <td><?php echo ($adv["id"]); ?></td>
                <td><image src="/Data/<?php echo ($adv["photo_x"]); ?>"  width="80px" height="80px"/></td>
                <td><?php echo ($adv["name"]); ?></td>

                <td><?php echo ($adv["price_yh"]); ?></td>
                <td><?php echo ($adv["price"]); ?></td>
                <td><?php echo ($adv["num"]); ?></td>
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
<script type="text/javascript" src="/Public/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/Public/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/Public/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/Public/admin/static/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/Public/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/Public/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/Public/admin/lib/laypage/1.2/laypage.js"></script>


<script>
    var type='<?php echo $type; ?>';

    function product_option(page){
        var adv_name = $('#name').val();
        location="<?php echo U('index');?>?adv_name="+adv_name;
    }

    function set_show(id){
        location="<?php echo U('show');?>?adv_id="+id;
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