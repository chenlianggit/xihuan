<?php
namespace Api\Controller;
use Think\Controller;
use Think\Log;
class WeChatTemplateController extends PublicController {
    public function _initialize() {
        $wx_config = C('weixin');
        $this->appid = $wx_config['appid'];
        $this->secret = $wx_config['secret'];
    }

    /**
     * 获取微信access_token，后面使用access_token来实现模板消息推送
     */
    public function getAccess_token(){


        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->secret;
        $AccessToken = $this->request_post($url);
        $AccessToken = json_decode($AccessToken , true);
        $AccessToken = $AccessToken['access_token'];
        $lifeTime = 7200;
        session_set_cookie_params($lifeTime);
        session_start();
        $_session["access_token"] = $AccessToken;

       echo json_encode(array('status'=>1,'access_token'=>$AccessToken));
        exit();

        //return $AccessToken;
    }

    public function request_post($url, $data){
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }else{
            return $tmpInfo;
        }
    }

    /**
     * 订单支付成功通知
     */
    public function orderPaySuccess(){

//        if ($_SESSION["access_token"]){
//            $AccessToken = $_SESSION["access_token"];
//        }else{
//            $AccessToken = $this->getAccess_token();
//        }

        $value = array(
            "keyword1"=>array(
                "value"=>'小米手机测试版',
                "color"=>"#4a4a4a"
            ),
            "keyword2"=>array(
                "value"=>'78978323429789',
                "color"=>"#4a4a4a"
            ),
            "keyword3"=>array(
                "value"=>'2017-12-03',
                "color"=>"#4a4a4a"
            ),
            "keyword4"=>array(
                "value"=>'0.01',
                "color"=>"#4a4a4a"
            )
        );

        $AccessToken  = 'KYYn9_phm2BYKS0bFJZIRVIW14kNoQZqek-haeCrGp6F7oykKaq_O7XiyG14kIXVav6yjGIzUyYXBHNvvduPtWmiwG9oNrIxGwS5stEGBKANKOdAGAXUY';
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$AccessToken;
        $dd['touser']='oOG4L0RcfRAVyjXKP_cshN5Q3by0';
        $dd['template_id']='hQ1HoSX87WVHYdYGYQVxNqdj7k4njRAALO5Ixj1DUWc';
        $dd['page']='';  //点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,该字段不填则模板无跳转。
        $dd['form_id']='wx20171203222444a4b62e5fba0859124883';
        $dd['data']=$value;                        //模板内容，不填则下发空模板
        $dd['color']='';                        //模板内容字体的颜色，不填默认黑色
        $dd['emphasis_keyword']='';

        $AccessToken1 = $this->sendTemplateMessage($url,$dd);
        //$AccessToken1 = json_decode($AccessToken , true);
        Log::write("orderPaySuccess:".$AccessToken1);
    }

    public function sendTemplateMessage($url,$data){
        //if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post($url,json_encode($data));
        if($result){
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }


    /* 发送json格式的数据，到api接口 -xzz0704  */
    function https_curl_json($url,$data,$type){
        if($type=='json'){//json $_POST=json_decode(file_get_contents('php://input'), TRUE);
            $headers = array("Content-type: application/json;charset=UTF-8","Accept: application/json","Cache-Control: no-cache", "Pragma: no-cache");
            $data=json_encode($data);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl);
        return $output;
    }

	//***************************
	//  首页数据接口
	//***************************
    public function index(){

    }

    //***************************
    //  首页产品 分页
    //***************************
    public function getlist(){
        $page = intval($_REQUEST['page']);
        if (!$page) {
            $page=2;
        }
        $limit = intval($page*8)-8;

        $pro_list = M('product')->where('del=0 AND is_down=0 AND type=1')->order('sort asc,addtime desc')->field('id,name,price_yh,price,photo_x,num,is_show,is_hot')->limit($limit.',8')->select();
        foreach ($pro_list as $k => $v) {
            $pro_list[$k]['photo_x'] = __DATAURL__.$v['photo_x'];
        }

        echo json_encode(array('prolist'=>$pro_list));
        exit();
    }

    public function getcode(){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<32;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        echo json_encode(array('status'=>'OK','code'=>$str));
        exit();
    }

    public function test(){
        echo __DATA__;
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    private function http_post($url,$param,$post_file=false){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

}