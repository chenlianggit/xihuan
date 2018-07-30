<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>




<style>
.dx1{float:left; margin-left: 17px; margin-bottom:10px; }
.dx2{color:#090; font-size:16px;  border-bottom:1px solid #CCC; width:100% !important; padding-bottom:8px;}
.dx3{width:120px; margin:5px auto; border-radius: 2px; border: 1px solid #b9c9d6; display:block;}
.dx4{border-bottom:1px solid #eee; padding-top:5px; width:100%;}
.img-err {
    position: relative;
    top: 2px;
    left: 87%;
    color: white;
    font-size: 20px;
    border-radius: 16px;
    background: #c00;
    height: 21px;
    width: 21px;
    text-align: center;
    line-height: 20px;
    cursor:pointer;
}
.btn{
            height: 25px;
            width: 60px;
            line-height: 24px;
            padding: 0 8px;
            background: #24a49f;
            border: 1px #26bbdb solid;
            border-radius: 3px;
            color: #fff;
            display: inline-block;
            text-decoration: none;
            font-size: 13px;
            outline: none;
            -webkit-box-shadow: #666 0px 0px 6px;
            -moz-box-shadow: #666 0px 0px 6px;
        }
        .btn:hover{
          border: 1px #0080FF solid;
          background:#D2E9FF;
          color: red;
          -webkit-box-shadow: rgba(81, 203, 238, 1) 0px 0px 6px;
          -moz-box-shadow: rgba(81, 203, 238, 1) 0px 0px 6px;
        }
        .cls{
            background: #24a49f;
        }
</style>


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
    <script type="text/javascript" src="/Public/admin/js/jCalendar.js"></script>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 分类管理 <span class="c-gray en">&gt;</span> 推荐产品 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">

    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-3"><a href="<?php echo U('index');?>">全部产品</a></label>
    </div>

    <br>

    <form class="form form-horizontal" action="<?php echo U('save_catpro');?>" method="post" onsubmit="return ac_from();" enctype="multipart/form-data">
        <ul class="aaa_pts_5">
            <li>
                <div class="d1">推荐产品:</div>
                <div>
                    <input class="inp_1" style="width:400px;" id="pro_name" value="<?php echo ($catimg["pro_name"]); ?>" disabled="disabled"/>
                    <input type="hidden" name="pro_id" id="pro_id" value="<?php echo ($catimg["pro_id"]); ?>"/>
                    <input type="button" value="选择产品" class="btn btn-primary radius" style="margin-left:15px;" onclick="win_open('<?php echo U('get_pro');?>?cat_id=<?php echo ($cat_id); ?>',1280,800)">
                </div>
            </li>
            <li>

                <div style="color:#c00; font-size:14px; padding-left:20px;">上传推荐展示图: 420*170，可添加多张&nbsp;&nbsp;&nbsp;<!-- 可多张 --></div>
            </li>
            <br>
            <?php if ($img) { ?>
            <li>
                <div class="d1">已上传：</div>
                <?php foreach ($img as $v) { ?>
                <div>
                    <div class="btn btn-primary radius" title="删除" onclick="del_img('<?php echo $v; ?>',this);">×</div>
                    <img src="<?php echo '/Data/'.$v; ?>" width="210" height="85">
                </div>
                <?php } ?>
            </li>
            <?php } ?>
            <li id="imgs_add">
                <div class="d1">展示图:</div>
                <div>
                    <input type="file" name="files[]" style="width:160px;" />
                </div>
            </li>
            <li>
                <div class="d1">&nbsp;</div>
                <div>
                    &nbsp;<span class="btn btn-primary radius"  onclick="upadd();">添加+</span>
                </div>
            </li>

            <li>
                <br>
                <input type="submit" name="submit" value="提交" class="btn btn-primary radius" border="0" id="aaa_pts_web_s">
                <input type="hidden" name="id" id='id' value="<?php echo ($catimg["id"]); ?>">
                <input type="hidden" name="cat_id" id='cat_id' value="<?php echo ($cat_id); ?>">
            </li>
        </ul>
    </form>


    


</div>
<script type="text/javascript" src="/Public/admin/js/product.js"></script>
<script>
function upadd(obj){
  //alert('aaa');
  $('#imgs_add').append('<div>&nbsp;&nbsp;<input type="file" style="width:160px;" name="files[]" /><a onclick="$(this).parent().remove();" class="btn cls" style="background:#D0D0D0; width:40px; color:black;"">&nbsp;&nbsp;&nbsp;删除</a></div>');
  return false;
}

//图片删除
function del_img(img,obj){
  var id = $('#id').val();
  if (confirm('是否确认删除？')) {
    $.post('<?php echo U("img_del");?>',{img_url:img,id:id},function(data){
      if(data.status==1){
        $(obj).parent().remove();
        return false;
      }else{
        alert(data.err);
        return false;
      }
    },"json");
  };
}

function ac_from(){
  var pro_id = $('#pro_id').val();
  if (!pro_id) {
    alert('请选择推荐产品!');
    return false;
  };
}
</script>
</body>
</html>