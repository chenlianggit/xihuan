<?php

/**
 * 检测输入的验证码是否正确，$code为用户输入的验证码字符串
 * @param  [type]  $code  [description]
 * @param  boolean $reset 是否重置验证码
 * @param  string  $id    [description]
 * @return [type]         [description]
 * @author jlb <[<email address>]>
 */
function check_verify($code, $reset = true, $id = ''){
    $verify = new \Think\Verify(array('reset'=>$reset));
    return $verify->check($code, $id);
}
/**
 * TODO 基础分页的相同代码封装，使前台的代码更少
 * @param $count int 要分页的总记录数
 * @param $pagesize int 每页查询条数
 * @return \Think\Page
 * @author Gison
 * @since  2016年09月02日
 */
function getPage($count, $pagesize = 20, $para = array())
{
    $p = new Think\Page($count, $pagesize, $para);
    $p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
    $p->setConfig('prev', '上一页');
    $p->setConfig('next', '下一页');
    $p->setConfig('last', '末页');
    $p->setConfig('first', '首页');
    $p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
    $p->lastSuffix = false;//最后一页不显示为总页数
    return $p;
}

/**
 * 加密函数
 *
 * @param $str string 要加密的字符串
 * @return string 加密后的字符串
 * @author Gison
 * @since  2016年12月07日
 */
function encrypt($str)
{
    return md5(C('AUTH_CODE') . $str);
}

/**
 * 生成箱子或优惠卷编码
 *
 * @param null
 * @return string 加密后的字符串
 * @author Gison
 * @since  2016年12月07日
 */
function get_encode()
{
    return md5(uniqid());
}

/**
 * 给菜单名生成树状结构
 * @param  [type] $arrTree 菜单数组
 * @param  [type] $step	   层次深度
 * @param  [type] $repeatStr 层次标识字符
 * @return array
 * @author jlb         
 */
function menuArrayTree($arrTree,$step=0,$repeatStr='---- ')
{
	static $trList = array();
	foreach ($arrTree as $v) 
	{
		$v['name'] = str_repeat($repeatStr, $step) . $v['name'];
		$trList[] = $v;
		if ( !empty($v['son']) ) 
		{
			menuArrayTree($v['son'], $step + 1);
		}
	}
	return $trList;
}

/**
 * Think 系统函数库
 */

/**
 * 获取和设置配置参数 支持批量定义
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */
//在线交易订单支付处理函数
//函数功能：根据支付接口传回的数据判断该订单是否已经支付成功；
//返回值：如果订单已经成功支付，返回true，否则返回false；
function checkorderstatus($ordid,$parameter){
    $row=M('order')->field('price,status')->where('ordernum='.$ordid)->select();
    file_put_contents("w.txt",$row[0]['price']);
    file_put_contents("ww.txt",$row[0]['status']);
    if($parameter==$row[0]['price']){//实际支付与订单价格相等
        //并且订单未支付
        return true;
    }else{
        return false;
    }
}
//处理订单函数
//更新订单状态，写入订单支付后返回的数据
function orderhandle($parameter){
    $ordid=$parameter['out_trade_no'];
    $data['payment_trade_no']      =$parameter['trade_no'];
    $data['payment_trade_status']  =$parameter['trade_status'];
    $data['payment_notify_id']     =$parameter['notify_id'];
    $data['payment_notify_time']   =$parameter['notify_time'];
    $data['payment_buyer_email']   =$parameter['buyer_email'];
    $data['ordstatus']             =1;
    $datas['status']=1;
    $datas['price_h']=$parameter['total_fee'];
    /*******解决屠涂同一订单重复支付问题 lisa**********/
    if(strlen($ordid)==16){//屠涂修改订单号唯一
        $ordstatus=M('order')->where('order_sn='.$ordid)->save($datas);
    }else{
        $ordstatus=M('order')->where('id='.$ordid)->save($datas);
    }
}
function i_array_column($input, $columnKey, $indexKey=null){
    if(!function_exists('array_column')){
        $columnKeyIsNumber  = (is_numeric($columnKey))?true:false;
        $indexKeyIsNull            = (is_null($indexKey))?true :false;
        $indexKeyIsNumber     = (is_numeric($indexKey))?true:false;
        $result                         = array();
        foreach((array)$input as $key=>$row){
            if($columnKeyIsNumber){
                $tmp= array_slice($row, $columnKey, 1);
                $tmp= (is_array($tmp) && !empty($tmp))?current($tmp):null;
            }else{
                $tmp= isset($row[$columnKey])?$row[$columnKey]:null;
            }
            if(!$indexKeyIsNull){
                if($indexKeyIsNumber){
                    $key = array_slice($row, $indexKey, 1);
                    $key = (is_array($key) && !empty($key))?current($key):null;
                    $key = is_null($key)?0:$key;
                }else{
                    $key = isset($row[$indexKey])?$row[$indexKey]:0;
                }
            }
            $result[$key] = $tmp;
        }
        return $result;
    }else{
        return array_column($input, $columnKey, $indexKey);
    }
}

/**
 * 飞鹅云打印
 * @param  int    $printer_sn  打印机序列号
 * @param  array  $orderInfo   订单信息
 * @param  int    $times       打印次数
 * @param  array  $ext         扩展信息
 * @return json
成功：{"msg":"ok","ret":0,"data":"316500004_20160823165104_1853029628","serverExecutedTime":6}
失败：{"msg":"错误信息.","ret":非零错误码,"data":null,"serverExecutedTime":5}
 */
function order_print($printer_sn, $orderInfo, $times, $ext){

    header("Content-type: text/html; charset=utf-8");
    $config = get_config('feieyun'); //读取配置

    $content = array(
        'user' => $config['user'],
        'stime' => time(),
        'sig' => sha1($config['user'] . $config['ukey'] . time()),
        'apiname' => 'Open_printMsg',

        'sn' => $printer_sn,
        'content' => $orderInfo,
        'times' => $times
    );

    $feieyun = new \Common\Util\Feieyun($config['ip'], $config['port']);

    $printLog = M("print_log");
    $data['store_id'] = $ext['store_id'];
    $data['printer_sn'] = $printer_sn;
    $data['request_time'] = time();

    $res = $feieyun->post($config['path'], $content);
    writeLogFile('feieyun', 'orderPrint:' . $res); //写日志

    if(!$res){
        $data['request_from'] = $ext['request_from'];
        $data['status'] = 0;
        $data['msg'] = 'error';
        $data['order_sn'] = $ext['order_sn'];
        $printLog->add($data);

        echo 'error';
    }else{
        $result = json_decode($feieyun->getContent());

        $data['status'] = 1;
        if($result->ret == 0){
            $data['msg'] = '['.$result->ret.'] ' . $result->msg;
        }else{
            $data['msg'] = '['.$result->ret.'] ' . $result->msg;
        }

        $data['request_from'] = $ext['request_from'];
        $data['order_sn'] = $ext['order_sn'];
        $printLog->add($data);

        echo $result->msg;
    }
}

/**
 * 查询打印机状态
 */
function queryPrinterStatus($printer_sn){
    $config = get_config('feieyun'); //读取配置

    $content = array(
        'user' => $config['user'],
        'stime' => time(),
        'sig' => sha1($config['user'] . $config['ukey'] . time()),
        'apiname' => 'Open_queryPrinterStatus',
        'sn' => $printer_sn
    );

    $feieyun = new \Common\Util\Feieyun($config['ip'], $config['port']);

    $res = $feieyun->post($config['path'], $content);
    writeLogFile('feieyun', 'queryPrinterStatus:' . $res); //写日志

    if(!$res){
        echo 'error';
    }else{
        $result = json_decode($feieyun->getContent(), true);
        $result['printer_sn'] = $printer_sn;
        return $result;
    }
}


/**
 *  post提交数据
 * @param  string $url 请求Url
 * @param  array $datas 提交的数据
 * @return url响应返回的html
 */
function sendPost($url, $datas) {
    $temps = array();
    foreach ($datas as $key => $value) {
        $temps[] = sprintf('%s=%s', $key, $value);
    }
    $post_data = implode('&', $temps);
    $url_info = parse_url($url);
    if(empty($url_info['port']))
    {
        $url_info['port']=80;
    }
    $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
    $httpheader.= "Host:" . $url_info['host'] . "\r\n";
    $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
    $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
    $httpheader.= "Connection:close\r\n\r\n";
    $httpheader.= $post_data;
    $fd = fsockopen($url_info['host'], $url_info['port']);
    fwrite($fd, $httpheader);
    $gets = "";
    $headerFlag = true;
    while (!feof($fd)) {
        if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
            break;
        }
    }
    while (!feof($fd)) {
        $gets.= fread($fd, 128);
    }
    fclose($fd);

    return $gets;
}



/**
 * Json方式 查询订单物流轨迹
 */
function getOrderTracesByJson($ShipperCode,$LogisticCode){
    $kdniao = C('kdniao');
    $EBusinessID = $kdniao['EBusinessID'];
    $AppKey = $kdniao['AppKey'];
    $ReqURL = $kdniao['ReqURL'];

    $requestData= "{'OrderCode':'','ShipperCode':'".$ShipperCode."','LogisticCode':'".$LogisticCode."'}";

    $datas = array(
        'EBusinessID' => $EBusinessID,
        'RequestType' => '1002',
        'RequestData' => urlencode($requestData) ,
        'DataType' => '2',
    );
    $datas['DataSign'] = encryptkdniao($requestData, $AppKey);
    $result=sendPost($ReqURL, $datas);

    //根据公司业务处理返回的信息......

    return $result;
}

/**
 * 电商Sign签名生成
 * @param data 内容
 * @param appkey Appkey
 * @return DataSign签名
 */
function encryptkdniao($data, $appkey) {
    return urlencode(base64_encode(md5($data.$appkey)));
}
