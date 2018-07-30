<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>

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
    <link href="/Public/ht/css/main.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/admin/js/jquery.js"></script>
    <script type="text/javascript" src="/Public/admin/js/action.js"></script>
<style>
.dx1{float:left; margin-left: 17px; margin-bottom:10px; }
.dx2{color:#090; font-size:16px;  border-bottom:1px solid #CCC; width:100% !important; padding-bottom:8px;}
.dx3{width:120px; margin:5px auto; border-radius: 2px; border: 1px solid #b9c9d6; display:block;}
.dx4{border-bottom:1px solid #eee; padding-top:5px; width:100%;}

.file {
    position: relative;
    display: inline-block;
    background: #D0EEFF;
    border: 1px solid #99D3F5;
    border-radius: 4px;
    padding: 4px 12px;
    overflow: hidden;
    color: #1E88C7;
    text-decoration: none;
    text-indent: 0;
    line-height: 20px;
}
.file input {
    position: absolute;
    font-size: 100px;
    right: 0;
    top: 0;
    opacity: 0;
}
.file:hover {
    background: #AADFFD;
    border-color: #78C3F3;
    color: #004974;
    text-decoration: none;
}
</style>
</head>
<body>
<script type="text/javascript">
  function uploadpic(id,obj){
    var filePath=$(obj).val();
    if(filePath.indexOf("jpg")!=-1 || filePath.indexOf("png")!=-1){
      $("#pic_"+id).val(filePath);
    }else{
      $("#pic_"+id).val("");
      alert("您未上传文件，或者您上传文件类型有误！");
      return false
    }
  }
</script>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 产品管理 <span class="c-gray en">&gt;</span> 属性规格管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">

    <div>
        <span style="color:red"><a href="<?php echo U('set_attr');?>?pid=<?php echo ($pro_id); ?>">设置规格</a></span> &nbsp;&nbsp;&nbsp;&nbsp;
        <span style="color:red"><a href="<?php echo U('pro_guige');?>?pid=<?php echo ($pro_id); ?>">规格管理</a></span>
        <!-- <div class="aaa_pts_4"><a href="<?php echo U('pro_attr');?>?pid=<?php echo ($pro_id); ?>">属性组合管理</a></div> -->
    </div>
    <br>
    <div class="aaa_pts_3">
        <span style="color:red">备注：若有产品属性组合不需要或库存不足，请将对应库存设置为0</span>
    </div>
    <br>

    <table class="table table-border table-bordered table-bg">
        <thead>

        <tr class="text-c">
            <th width="30">规格ID</th>
            <th width="40">属性名</th>
            <th width="50">规格名称</th>
            <th width="60">价格/元<span style="color:red;">(双击修改)</span></th>
            <th width="60">库存<span style="color:red;">(双击修改)</span></th>

            <th width="120">操作</th>
        </tr>
        </thead>


        <tbody id="news_option">
        <!-- 遍历 -->
        <?php if(is_array($guige_list)): $i = 0; $__LIST__ = $guige_list;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr class="text-c">
                <td><?php echo ($v["id"]); ?></td>
                <td><?php echo ($v["attr_name"]); ?></td>
                <td><?php echo ($v["name"]); ?></td>
                <td id="td_<?php echo ($v["id"]); ?>"><span ondblclick="upprice(<?php echo ($v["id"]); ?>,this);"><?php echo ($v["price"]); ?></span></td>
                <td id="tds_<?php echo ($v["id"]); ?>"><span ondblclick="upstock(<?php echo ($v["id"]); ?>,this);"><?php echo ($v["stock"]); ?></span></td>


                <td>
                    <a href="#" onclick="del_attr(<?php echo ($v["id"]); ?>);">删除</a>
                </td>

            </tr><?php endforeach; endif; else: echo "暂时没有数据" ;endif; ?>
        <!-- 遍历 -->
        </tbody>

    </table>
</div>




<script type="text/javascript">
  function upprice(id,obj){
    var price = $(obj).html();
    $(obj).html('<input type="text" class="inp_1" style="width:80px;margin-left:22px;" name="price_'+id+'" id="price_'+id+'" value="'+price+'" onblur="ajax_upprice('+price+','+id+');">');
    $(obj).children().focus();
    $(obj).removeAttr('ondblclick');
  }

  function upstock(id,obj){
    var stock = $(obj).html();
    $(obj).html('<input type="text" class="inp_1" style="width:80px;margin-left:22px;" name="stock_'+id+'" id="stock_'+id+'" value="'+stock+'" onblur="ajax_upstock('+stock+','+id+');">');
    $(obj).children().focus();
    $(obj).removeAttr('ondblclick');
  }

  //ajax修改价格
  function ajax_upprice(price,id){
    var up_price = document.getElementById('price_'+id).value;
    if (price==up_price) {
      $('#td_'+id).html('<span ondblclick="upprice('+id+',this);">'+price.toFixed(2)+'</span>');
      return ;
    };

    var fix_amountTest=/^(([1-9]\d*)|\d)(\.\d{1,2})?$/;
    if(fix_amountTest.test(up_price)==false){
      alert("请输入有效金额！");
      return false;
    }

    //ajax
    $.post('<?php echo U("ajax_up");?>',{pro_id:'<?php echo ($pro_id); ?>',vals:up_price,id:id,type:'pro_price'},function(data){
      if (data.status==1) {
        $('#td_'+id).html('<span ondblclick="upprice('+id+',this);">'+up_price+'</span>');
      }else{
        alert(data.err);
        return false;
      }
    },'json');
  }

  //ajax修改库存
  function ajax_upstock(stock,id){
    var up_stock = document.getElementById('stock_'+id).value;
    if (stock==up_stock) {
      $('#tds_'+id).html('<span ondblclick="upstock('+id+',this);">'+stock+'</span>');
      return ;
    };

    if(/^\d+$/.test(up_stock)==false) {
      alert('请输入数字格式的库存！');
      return false;
    }

    //ajax
    $.post('<?php echo U("ajax_up");?>',{pro_id:'<?php echo ($pro_id); ?>',vals:up_stock,id:id,type:'pro_stock'},function(data){
      if (data.status==1) {
        $('#tds_'+id).html('<span ondblclick="upstock('+id+',this);">'+up_stock+'</span>');
      }else{
        alert(data.err);
        return false;
      }
    },'json');
  }

  function uploadimg(id){
    //alert(id);
    $('#form_'+id).submit();
  }

  //删除
  function del_attr(id){
    if (confirm('删除后产品价格库存将需要重置，确定删除吗？')) {
      window.location.href="<?php echo U('del_guige');?>?gg_id="+id;
    };
  }
</script>
</body>
</html>