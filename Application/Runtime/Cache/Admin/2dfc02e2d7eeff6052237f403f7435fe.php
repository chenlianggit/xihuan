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
    <script type="text/javascript" src="/dev/Public/plugins/xheditor/xheditor-1.2.1.min.js"></script>
    <script type="text/javascript" src="/dev/Public/plugins/xheditor/xheditor_lang/zh-cn.js"></script>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 文章管理 <span class="c-gray en">&gt;</span> <?php if($_GET['news_id']) { ?><a href="#">修改文章</a><?php }else{ ?><a href="<?php echo U('add');?>">添加文章</a><?php } ?> <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <form class="form form-horizontal" action="<?php echo U('save');?>" method="post" onsubmit="return ac_from();" enctype="multipart/form-data">

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>文章标题：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <input type="text" class="input-text" placeholder="文章标题" name="name" id="name" value="<?php echo $news['name']; ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>所属分类：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <select class="inp_1" name="cid" id="cid">
                    <?php if(is_array($cate_list)): $i = 0; $__LIST__ = $cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i; if($cate["id"] == $news['cid']): ?><option value="<?php echo ($cate["id"]); ?>" selected="selected" >- <?php echo ($cate["name"]); ?></option>
                            <?php else: ?>
                            <option value="<?php echo ($cate["id"]); ?>" >- <?php echo ($cate["name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>文章摘要：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <textarea class="inp_1 inp_8" name="digest" id="digest" cols="60" rows=10 /><?php echo $news['concent']; ?></textarea>
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>200px*200px,格式为jpg、jpeg、png</label>
            <div class="formControls col-xs-8 col-sm-3">
                <?php if($news['photo'] != ''): ?><img src="/dev/Data/<?php echo $news['photo']; ?>" /><?php endif; ?>
                <input type="hidden" name="photo" id="photo" value="<?php echo $news['photo']; ?>"/>
                <input type="file" name="file" id="photo" />
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>文章内容：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <textarea class="" name="content" id="content" cols="120" rows=30 /><?php echo $news['content']; ?></textarea>
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>文章来源：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <input type="text" class="input-text" placeholder="文章来源" name="source" id="source" value="<?php echo $news['source']; ?>">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>浏览次数：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <input type="text" class="input-text" placeholder="浏览次数" name="renqi" id="renqi" value="<?php echo (int)$news['renqi']?>">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>排 序：</label>
            <div class="formControls col-xs-8 col-sm-3">
                <input type="text" class="input-text" placeholder="排序" name="sort" id="sort" value="<?php echo (int)$news['sort'] ?>">
            </div>
        </div>

        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                <input class="btn btn-primary radius" type="submit" name="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
                <input type="hidden" name="news_id" value="<?php echo (int)$news['id']; ?>"/>
            </div>
        </div>
    </form>
</div>




<script>
function ac_from(){

  var name=document.getElementById('name').value;
  
  if(name.length<2){
	  alert('文章名称不能少于2');
	  return false;
	  } 
  
  var pid=parseInt(document.getElementById('pid').value);
  if(isNaN(pid) || pid<1){
	  alert('请选择分类');
	  return false;
	  } 
	  
}

//初始化编辑器
$('#content').xheditor({
	skin:'nostyle' ,
	upImgUrl:'<?php echo U("Upload/xheditor");?>'
});
</script>
</body>
</html>