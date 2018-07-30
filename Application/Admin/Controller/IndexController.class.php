<?php
namespace Admin\Controller;

use Think\Controller;

/**
 * @author jlb
 * @since 2016年12月7日09:57:17 
 */
class IndexController extends CommonController
{
	/**
	 * 显示后台首页
	 * @author  jlb
	 * @return [type] [description]
	 */
    public function index()
	{
		//未登录,则显示登录页
		if ( !$this->admin_id ) 
		{
			$this->display('login');
			die;
		}
		//将数组组装成树状结构
		$this->assign('menuList', array_tree($this->privilegeList,'menu_id'));
		$this->assign('uname', session('adminInfo.uname'));

		$this->display();
	}

	public function welcome()
	{
	}

	/**
	 * 后台登录
	 * @author jlb <[<email address>]>
	 * @return [type] [description]
	 */
	public function login()
	{
		if ( !IS_POST ) 
		{
			$this->error('非法请求','Index/index');
		}

		$uname = I('post.uname', '', 'trim');
		$pword = I('post.pword', '', 'trim');
		$verifycode = I('post.verifycode', '', 'trim');

		if ( !$uname ) 
		{
			$this->error('请输入账号');
		}
		if ( !$pword ) {
			$this->error('请输入密码');
		}
		if ( !$verifycode ) 
		{
			$this->error('请输入图形验证码');
		}
		elseif(!check_verify($verifycode, false))
		{
			$this->error('图形验证码不正确');
		}
		$whereLogin['uname'] = $uname;
		$whereLogin['pword'] = encrypt($pword);

		$user = M('Admin')->where($whereLogin)->find();

		if ( !$user ) 
		{
			$this->error('账号或者密码错误!');
		}

		//登录成功
		check_verify($verifycode); //失效验证码
		//添加进session
		session('adminInfo', $user);
		//跳转到首页
		$this->redirect('index');
	}


	/**
	 * 退出登录
	 * @return [type] [description]
	 */
	public function logout()
	{
		session_destroy();
		$this->redirect('index');
	}
        public function qr() {

                $appid = 'wx614a3726bf502f35';
                $app_secret = 'e6937f1623c387c25be9906aa2072ba2';

                $data = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$app_secret);
                $data = json_decode($data,true);

                $qr_url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token={$data['access_token']}";
                #$qr_url = "https://api.weixin.qq.com/wxa/getwxacode?access_token={$data['access_token']}";

                $qr_data = ['path'=>'pages/ritual/ritual?source=tie_qr','width'=>680];

                $result_data = $this->curl("post",$qr_url,$qr_data);

                //$save_file_path = "/";


                $base_dir = dirname(dirname(dirname(dirname(__FILE__))));

                $upload_dir = "/qr/f.png";

                file_put_contents($base_dir.$upload_dir,$result_data);

                $time = time();

                echo "<span style='color:red;font-size:28px;'>�~K载�~[��~I~G�~V��~O�~Z�| �| ~G�~O��~T��~O��~X为�~[��~I~G�~@~B</span><br/><img src='{$result_data}' style='width:340px;height:340px;'/>";

        }

        public function curl($type,$url,$data=''){
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_POST, 0);
                if($type == 'post') {
                        curl_setopt($curl, CURLOPT_POST, 1);
                }
                if($data) {
                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $ret = curl_exec($curl);
                curl_close($curl);
//              return json_decode($ret,TRUE);
                return $ret;
        }
}
