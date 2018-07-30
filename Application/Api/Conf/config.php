<?php
//var_dump('11');exit;
header("Content-Type:text/html; charset=utf-8");
error_reporting(0);
define('SELF_ROOT','https://xxx.xxxxx.com/');
$urkn= SELF_ROOT."Data/app/";
define('APP_URL',$urkn);
//$host='42.120.217.37';
return array(
    'key'         => 15222,
    'URL_MODEL'   => 0,
    'app_name'    => '小程序',
    'DB_FIELDS_CACHE'       => true,
    //'base'					=>$urkn.ceil($sckey/50).'/'.$sckey.md5($sckey).'/' ,
    'base'					=> $urkn.'62/3057c1502ae5a4d514baec129f72948c266e/',
    //'url'                     =>ceil($sckey/50).'/'.$sckey.md5($sckey),
    'TMPL_CACHE_ON'         => false,//禁止模板编译缓存
    'HTML_CACHE_ON'         => false,//禁止静态缓存
    'LOG_RECORD'            => false,   // 默认不记录日志
    'LOG_TYPE'              => 'File', // 日志记录类型 默认为文件方式
    'LOG_LEVEL'             => 'EMERG,ALERT,CRIT,ERR',// 允许记录的日志级别
    'LOG_EXCEPTION_RECORD'  => false,
    'LOAD_EXT_CONFIG'       => "functions",
    //'__DATA__'=>__ROOT__.'/Data',
    //更换模板变量规则，修改配置项
    'TMPL_PARSE_STRING'     => array(           //添加自己的模板变量规则
        '__DATA__'=>__ROOT__.'/Data'
    ),
    'TMPL_ACTION_ERROR'     =>  'Public/error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  'Public/success', // 默认成功跳转对应的模板文件

    //以上配置项，是从接口包中alipay.config.php 文件中复制过来，进行配置；

    //微信配置参数
    'weixin'=>array(
        'appid'     => 'wx614a3726bf502f35',			//小程序appid
        'secret'    => 'e6937f1623c387c25be9906aa2072ba2', //小程序secret
        'mchid'     => '', //商户号
        'key'       => '',  //支付秘钥
        //这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
        'notify_url'=> 'https://xxx.xxx.com/index.php/Api/Wxpay/notify',
    ),

    //快递鸟物流查询配置参数
    'kdniao'=>array(
        'EBusinessID'     => '1321731',//请到快递鸟官网申请http://kdniao.com/reg
        'AppKey'    => '4d02574a-3ec1-4e03-8c31-7514743df148', //请到快递鸟官网申请http://kdniao.com/reg
        'ReqURL'     => 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx',
    ),
);

?>
