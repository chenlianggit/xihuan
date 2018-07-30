<?php
namespace Api\Controller;
use Think\Controller;
/**
 * 快递单信息查询类
 * 支持“快递100” 和 “快递鸟”两个渠道接口
 * “快递鸟”接口需要配置“appID,appKey”两个参数“商户ID,商户密钥”
 * “快递100”接口需要配置“appKey"参数“授权密钥”
 * @author sunnyzeng <89781816@qq.com>
 * @since v1.0.0 [快递单物流信息查询]
 */
class ExpressController extends PublicController {
	// 商户ID 
	private $appID  = '';
	// 授权密钥
	private $appKey = '';
	/**
	 * 查询快递物流信息
	 * @return [type] [description]
	 */
	public function getExpressInfo(){
		$channel 	= $_POST['channel'];
		$comCode 	= $_POST['comcode'];
		$orderCode 	= $_POST['ordercode'];
		if ($channel == 'kuaidi100') {
			$this->getKuaiDi100ExpressInfo();
		}elseif($channel == 'kdniao'){
			$this->getKDniaoExpressInfo();
		}
	}	
	/**
	 * 使用快递100接口查询快递单物流信息
	 * @param  [string] $comCode   [快递公司编码]
	 * @param  [string] $orderCode [快递单号]
	 * @return [string]            [description]
	 */
	private function getKuaiDi100ExpressInfo($comCode,$orderCode){
		$com  = $_POST['com'];
		$code = $_POST['code'];
		$api  = 'http://api.kuaidi100.com/api?id=%s&com=%s&nu=%s&show=0&muti=1&order=desc';
		$data = $this->request(sprintf($api,$this->appKey,$com,$code));
		$response 	= ['status'=>0,'data'=>[],'state'=>0,'message'=>'快递单号不存在'];
		if (!empty($data)) {
			$data 	= json_decode($data,true);
			$response = [
				'status'	=> $data['status'], // 0：物流单暂无结果，1：查询成功， 2：接口出现异常
				'data'  	=> $data['data'],
				'state' 	=> $this->parseState($data['state'],1),
				'message'	=> $data['message']
			];
			// 快递单当前的状态 ：　 
			// 0：在途，即货物处于运输过程中；
			// 1：揽件，货物已由快递公司揽收并且产生了第一条跟踪信息；
			// 2：疑难，货物寄送过程出了问题；
			// 3：签收，收件人已签收；
			// 4：退签，即货物由于用户拒签、超区等原因退回，而且发件人已经签收；
			// 5：派件，即快递正在进行同城派件；
			// 6：退回，货物正处于退回发件人的途中；
		}
		echo json_encode($response);
	}

	/**
	 * 使用快递鸟接口查询快递单物流信息
	 * @param  [string] $comCode   [快递公司编码]
	 * @param  [string] $orderCode [快递单号]
	 * @return [string]            [description]
	 */
	private function getKDniaoExpressInfo($comCode,$orderCode){
		$requestData = ['OrderCode'=>'','ShipperCode'=>$comCode,'LogisticCode'=>$orderCode];
		$DataSign 	 = urlencode(base64_encode(md5(json_encode($requestData.$this->appKey))));
		$postData = [
			'RequestType' => 1002,
			'EBusinessID' => $this->appID,
			'RequestData '=> urlencode(json_encode($requestData)),
			'DataSign '	  => $DataSign,
			'DataType' 	  => 2
		];
		$api  		= 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx';
		$data 		= $this->request($api,$postData);
		$response 	= ['status'=>0,'data'=>[],'state'=>0,'message'=>'快递单号不存在'];
		if (!empty($data)) {
			$data 	= json_decode($data,true);
			$response = [
				'status'	=> $data['Success'] ? 1 : 0,
				'data'  	=> $data['Traces'],
				'state' 	=> $this->parseState($data['State'],2),
				'message'	=> $data['Reason']
			];
		}
		echo json_encode($response);
	}

	// 解析快递单号状态
	// 快递单当前的状态 ：　 
	// 0：在途，即货物处于运输过程中；
	// 1：揽件，货物已由快递公司揽收并且产生了第一条跟踪信息；
	// 2：疑难，货物寄送过程出了问题；
	// 3：签收，收件人已签收；
	// 4：退签，即货物由于用户拒签、超区等原因退回，而且发件人已经签收；
	// 5：派件，即快递正在进行同城派件；
	// 6：退回，货物正处于退回发件人的途中；
	private function parseState($state,$type){
		$status = 0;

		if ($type==1) {
			// 快递100
			switch ($state) {
				case '0': // 在途
					$status = 0;
					break;
				case '1': // 揽件
					$status = 1;
					break;
				case '2': // 问题件
					$status = 2;
					break;
				case '3': // 签收
					$status = 3;
					break;
				case '4': // 退签
					$status = 4;
					break;
				case '5': // 派件
					$status = 5;
					break;
				case '6': // 退回
					$status = 6;
					break;
				default:
					$status = $state;
					break;
			}
		}else{
			// 快递鸟
			switch ($state) {
				case '0': // 无订单
					$status = -1;
					break;
				case '2': // 在途中
					$status = 0;
					break;
				case '3': // 签收
					$status = 3;
					break;
				case '4': // 问题件
					$status = 2;
					break;
				default:
					$status = $state;
					break;
			}
		}
		return $status;
	}

	/**
	 * 获取快递公司编码
	 * @return [type] [description]
	 */
	public function getExpressCode(){
		$channel = $_POST['ch'];
		$expressCode = [];
		if ($channel == 1) {
			$expressCode = $this->getKuaiDi100ExpressCode();
		}elseif ($channel == 2) {
			$expressCode = $this->getKDniaoExpressCode();
		}
		echo json_encode(['status'=>1, 'data'=>$expressCode]); 
	}

	/**
	 * 快递鸟接口支持的物流公司编码
	 * @return [type] [description]
	 */
	private function getKDniaoExpressCode()
	{
		return [
			'AJ'	=>'安捷快递',
			'ANE'	=>'安能物流',
			'AXD'	=>'安信达快递',
			'BQXHM'	=>'北青小红帽',
			'BFDF'	=>'百福东方',
			'BTWL'	=>'百世快运',
			'CCES'	=>'CCES快递',
			'CITY100'=>'城市100',
			'COE'	=>'COE东方快递',
			'CSCY'	=>'长沙创一',
			'CDSTKY'=>'成都善途速运',
			'DBL'	=>'德邦',
			'DSWL'	=>'D速物流',
			'DTWL'	=>'大田物流',
			'EMS'	=>'EMS',
			'FAST'	=>'快捷速递',
			'FEDEX'	=>'FEDEX联邦(国内件）',
			'FEDEX_GJ'=>'FEDEX联邦(国际件）',
			'FKD'	=>'飞康达',
			'GDEMS'	=>'广东邮政',
			'GSD'	=>'共速达',
			'GTO'	=>'国通快递',
			'GTSD'	=>'高铁速递',
			'HFWL'	=>'汇丰物流',
			'HHTT'	=>'天天快递',
			'HLWL'	=>'恒路物流',
			'HOAU'	=>'天地华宇',
			'hq568'	=>'华强物流',
			'HTKY'	=>'百世快递',
			'HXLWL'	=>'华夏龙物流',
			'HYLSD'	=>'好来运快递',
			'JGSD'	=>'京广速递',
			'JIUYE'	=>'九曳供应链',
			'JJKY'	=>'佳吉快运',
			'JLDT'	=>'嘉里物流',
			'JTKD'	=>'捷特快递',
			'JXD'	=>'急先达',
			'JYKD'	=>'晋越快递',
			'JYM'	=>'加运美',
			'JYWL'	=>'佳怡物流',
			'KYWL'	=>'跨越物流',
			'LB'	=>'龙邦快递',
			'LHT'	=>'联昊通速递',
			'MHKD'	=>'民航快递',
			'MLWL'	=>'明亮物流',
			'NEDA'	=>'能达速递',
			'PADTF'	=>'平安达腾飞快递',
			'QCKD'	=>'全晨快递',
			'QFKD'	=>'全峰快递',
			'QRT'	=>'全日通快递',
			'RFD'	=>'如风达',
			'SAD'	=>'赛澳递',
			'SAWL'	=>'圣安物流',
			'SBWL'	=>'盛邦物流',
			'SDWL'	=>'上大物流',
			'SF'	=>'顺丰快递',
			'SFWL'	=>'盛丰物流',
			'SHWL'	=>'盛辉物流',
			'ST'	=>'速通物流',
			'STO'	=>'申通快递',
			'STWL'	=>'速腾快递',
			'SURE'	=>'速尔快递',
			'TSSTO'	=>'唐山申通',
			'UAPEX'	=>'全一快递',
			'UC'	=>'优速快递',
			'WJWL'	=>'万家物流',
			'WXWL'	=>'万象物流',
			'XBWL'	=>'新邦物流',
			'XFEX'	=>'信丰快递',
			'XYT'	=>'希优特',
			'XJ'	=>'新杰物流',
			'YADEX'	=>'源安达快递',
			'YCWL'	=>'远成物流',
			'YD'	=>'韵达快递',
			'YDH'	=>'义达国际物流',
			'YFEX'	=>'越丰物流',
			'YFHEX'	=>'原飞航物流',
			'YFSD'	=>'亚风快递',
			'YTKD'	=>'运通快递',
			'YTO'	=>'圆通速递',
			'YXKD'	=>'亿翔快递',
			'YZPY'	=>'邮政平邮/小包',
			'ZENY'	=>'增益快递',
			'ZHQKD'	=>'汇强快递',
			'ZJS'	=>'宅急送',
			'ZTE'	=>'众通快递',
			'ZTKY'	=>'中铁快运',
			'ZTO'	=>'中通速递',
			'ZTWL'	=>'中铁物流',
			'ZYWL'	=>'中邮物流',
			'AMAZON'=>'亚马逊物流',
			'SUBIDA'=>'速必达物流',
			'RFEX'	=>'瑞丰速递',
			'QUICK'	=>'快客快递',
			'CJKD'	=>'城际快递',
			'CNPEX'	=>'CNPEX中邮快递',
			'HOTSCM'=>'鸿桥供应链',
			'HPTEX'	=>'海派通物流公司',
			'AYCA'	=>'澳邮专线',
			'PANEX'	=>'泛捷快递',
			'PCA'	=>'PCA Express',
			'UEQ'	=>'UEQ Express'
		];
	}
	/**
	 * 快递100支持的物流公司编码
	 * @return [type] [description]
	 */
	private function getKuaiDi100ExpressCode(){
		return [
			'auspost'		=>'澳大利亚邮政',
			'aae'			=>'AAE',
			'anxindakuaixi'	=>'安信达',				
			'huitongkuaidi'	=>'百世汇通',
			'baifudongfang'	=>'百福东方',
			'bht'			=>'BHT',
			'youzhengguonei'=>'包裹/平邮/挂号信',
			'bangsongwuliu'	=>'邦送物流',			
			'cces'			=>'希伊艾斯（CCES）',
			'coe'			=>'中国东方（COE）',
			'chuanxiwuliu'	=>'传喜物流',
			'canpost'		=>'加拿大邮政Canada Post（英文结果）',
			'canpostfr'		=>'加拿大邮政Canada Post(德文结果）',			
			'datianwuliu'	=>'大田物流',
			'debangwuliu'	=>'德邦物流',
			'dpex'			=>'DPEX',
			'dhl'			=>'DHL-中国件-中文结果',
			'dhlen'			=>'DHL-国际件-英文结果',
			'dhlde'			=>'DHL-德国件-德文结果',
			'dsukuaidi'		=>'D速快递',
			'disifang'		=>'递四方',			
			'ems'			=>'EMS(中文结果)',
			'ems'			=>'E邮宝',
			'emsen'			=>'EMS（英文结果）',
			'emsguoji'		=>'EMS-（中国-国际）件-中文结果/EMS-(China-International）-Chinese data',
			'emsinten'		=>'EMS-（中国-国际）件-英文结果/EMS-(China-International）-Englilsh data',				
			'fedex'			=>'Fedex-国际件-英文结果',
			'fedexcn'		=>'Fedex-国际件-中文结果',
			'fedexus'		=>'Fedex-美国件-英文结果',
			'feikangda'		=>'飞康达物流',
			'feikuaida'		=>'飞快达',
			'rufengda'		=>'凡客如风达',
			'fengxingtianxia'=>'风行天下',
			'feibaokuaidi'	=>'飞豹快递',		
			'ganzhongnengda'=>'港中能达',
			'guotongkuaidi'	=>'国通快递',
			'guangdongyouzhengwuliu'=>'广东邮政',
			'youzhengguonei'=>'挂号信',
			'youzhengguonei'=>'国内邮件',
			'youzhengguoji'	=>'国际邮件',
			'gls'			=>'GLS',
			'gongsuda'		=>'共速达',		
			'huitongkuaidi'	=>'汇通快运',
			'huiqiangkuaidi'=>'汇强快递',
			'tiandihuayu'	=>'华宇物流',
			'hengluwuliu'	=>'恒路物流',
			'huaxialongwuliu'=>'华夏龙',
			'tiantian'		=>'海航天天',
			'haiwaihuanqiu'	=>'海外环球',
			'hebeijianhua'	=>'河北建华',
			'haimengsudi'	=>'海盟速递',
			'huaqikuaiyun'	=>'华企快运',
			'haihongwangsong'=>'山东海红',				
			'jiajiwuliu'	=>'佳吉物流',
			'jiayiwuliu'	=>'佳怡物流',
			'jiayunmeiwuliu'=>'加运美',
			'jinguangsudikuaijian'=>'京广速递',
			'jixianda'		=>'急先达',
			'jinyuekuaidi'	=>'晋越快递',
			'jietekuaidi'	=>'捷特快递',
			'jindawuliu'	=>'金大物流',
			'jialidatong'	=>'嘉里大通',				
			'kuaijiesudi'	=>'快捷速递',
			'kangliwuliu'	=>'康力物流',
			'kuayue'		=>'跨越物流',			
			'lianhaowuliu'	=>'联昊通',
			'longbanwuliu'	=>'龙邦物流',
			'lanbiaokuaidi'	=>'蓝镖快递',
			'lejiedi'		=>'乐捷递',
			'lianbangkuaidi'=>'联邦快递（Fedex-中国-中文结果）',
			'lianbangkuaidien'=>'联邦快递(Fedex-中国-英文结果）',
			'lijisong'		=>'立即送',
			'longlangkuaidi'=>'隆浪快递',				
			'menduimen'		=>'门对门',
			'meiguokuaidi'	=>'美国快递',
			'mingliangwuliu'=>'明亮物流',				
			'ocs'			=>'OCS',
			'ontrac'		=>'onTrac',				
			'quanchenkuaidi'=>'全晨快递',
			'quanjitong'	=>'全际通',
			'quanritongkuaidi'=>'全日通',
			'quanyikuaidi'	=>'全一快递',
			'quanfengkuaidi'=>'全峰快递',
			'sevendays'		=>'七天连锁',				
			'rufengda'		=>'如风达快递',				
			'shentong'		=>'申通',
			'shunfeng'		=>'顺丰',
			'shunfengen'	=>'顺丰（英文结果）',
			'santaisudi'	=>'三态速递',
			'shenghuiwuliu'	=>'盛辉物流',
			'suer'			=>'速尔物流',
			'shengfengwuliu'=>'盛丰物流',
			'shangda'		=>'上大物流',
			'santaisudi'	=>'三态速递',
			'haihongwangsong'=>'山东海红',
			'saiaodi'		=>'赛澳递',
			'haihongwangsong'=>'山东海红',
			'sxhongmajia'	=>'山西红马甲',
			'shenganwuliu'	=>'圣安物流',
			'suijiawuliu'	=>'穗佳物流',				
			'tiandihuayu'	=>'天地华宇',
			'tiantian'		=>'天天快递',
			'tnt'			=>'TNT（中文结果）',
			'tnten'			=>'TNT（英文结果）',
			'tonghetianxia'	=>'通和天下',
			'ups'			=>'UPS（中文结果）',
			'upsen'			=>'UPS（英文结果）',
			'youshuwuliu'	=>'优速物流',
			'usps'			=>'USPS（中英文）',			
			'wanjiawuliu'	=>'万家物流',
			'wanxiangwuliu'	=>'万象物流',
			'weitepai'		=>'微特派',
			'xinbangwuliu'	=>'新邦物流',
			'xinfengwuliu'	=>'信丰物流',
			'xinbangwuliu'	=>'新邦物流',
			'neweggozzo'	=>'新蛋奥硕物流',
			'hkpost'		=>'香港邮政',				
			'yuantong'		=>'圆通速递',
			'yunda'			=>'韵达快运',
			'yuntongkuaidi'	=>'运通快递',
			'youzhengguonei'=>'邮政小包（国内）',
			'youzhengguoji'	=>'邮政小包（国际）',
			'yuanchengwuliu'=>'远成物流',
			'yafengsudi'	=>'亚风速递',
			'yibangwuliu'	=>'一邦速递',
			'youshuwuliu'	=>'优速物流',
			'yuanweifeng'	=>'源伟丰快递',
			'yuanzhijiecheng'=>'元智捷诚',
			'yuefengwuliu'	=>'越丰物流',
			'yuananda'		=>'源安达',
			'yuanfeihangwuliu'=>'原飞航',
			'zhongxinda'	=>'忠信达快递',
			'zhimakaimen'	=>'芝麻开门',
			'yinjiesudi'	=>'银捷速递',
			'yitongfeihong'	=>'一统飞鸿',				
			'zhongtong'		=>'中通速递',
			'zhaijisong'	=>'宅急送',
			'zhongyouwuliu'	=>'中邮物流',
			'zhongxinda'	=>'忠信达',
			'zhongsukuaidi'	=>'中速快件',
			'zhimakaimen'	=>'芝麻开门',
			'zhengzhoujianhua'=>'郑州建华',
			'zhongtianwanyun'=>'中天万运',
		];
	}

	/**
	 * 发起网络请求
	 * @param  [type] $url    [description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function request($url,$params)
	{
		$curl = curl_init();
		curl_setopt ($curl, CURLOPT_URL, $url);
		curl_setopt ($curl, CURLOPT_HEADER,0);
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($curl, CURLOPT_TIMEOUT,5);
		if (!empty($params) && is_array($params)) {
			curl_setopt($curl,CURLOPT_POSTFIELDS,$params);
		}
		try {			
			$response = curl_exec($curl);
		} catch (Exception $e) {
			$response = false;
		}
		curl_close ($curl);
		return $response;
	}
}