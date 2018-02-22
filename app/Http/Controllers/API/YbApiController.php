<?php
/**
 * 易班接口访问
 * 
 */
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class YbApiController extends Controller
{
    public $config = array(
        'AppID'     => '07f4098d7cb603b5',  //此处填写你的appid
        'AppSecret' => 'da76662a03c443fc794e6cd1bc0e9a4d',  //此处填写你的AppSecret
        'code'      => '',   //授权码
        'info'      => '',   //令牌信息存储
        'user'      => '',   //user信息
    );
    const CODE_REDIRECT = 'http://139.199.79.172/yiban/public/token';   //获取code后跳转地址
    const TOKEN_REDIRECT = 'http://139.199.79.172/yiban/public/token';   //获取token后跳转地址
    const PUBLIC_OPTION = 'https://openapi.yiban.cn/';   //查询信息公用开头网址部分
    const OAUTH_CODE = self::PUBLIC_OPTION.'oauth/authorize';   //获取code
    const OAUTH_TOKEN = self::PUBLIC_OPTION.'oauth/access_token';   //获取token
    const TOKEN_QUERY = self::PUBLIC_OPTION.'oauth/token_info';   //获取token信息
    const TOKEN_REVOKE = self::PUBLIC_OPTION.'oauth/revoke_token';   //取消授权
    

    public function __construct(){
    }

    /**
     * 错误跳转页面
     */
    public function errorPage(){
        return view('errors.error403');
    }
    
    /**
     * 跳转主页
     *
     * @return void
     */
    public function index(){
        if(empty($this->config['info']) || empty($this->config['code'])){
            return view('welcome');
        }
        return view('index');
    }
    
    /**
     * 获取用户信息
     *
     * @return array
     */
    public function getUser(){
        if(empty(session('token'))){
            $error = '授权失败，请重新授权';
            return view('welcome', compact('error'));
        }

        $url = self::PUBLIC_OPTION.'user/me';
        $param = array();
        $param['access_token'] = session('token');
        $this->config['user'] = $this->queryURL($url, $param);
    }

    /**
     * 取消用户授权
     *
     * @return mixed
     */
    public function revoke(Request $request){
        if((session('token')===NULL || session('token')==='')){
            // var_dump(session('token'));
            // var_dump($this->config['info']);
            $error = '操作失败，请重新授权';
            return view('welcome', compact('error'));
        }

        $url = self::PUBLIC_OPTION.'oauth/revoke_token';
        $param = array();
        $param['access_token'] = session('token');
        $param['client_id'] = $this->config['AppID'];
        $result = $this->config['user'] = $this->queryURL($url, $param, true);

        // 注销回话的token
        session(['token'=>'']);
        
        $msg = '注销成功，请重新授权';
        return view('welcome', compact('msg'));
    }

    /**
     * 访问接口，获取json数据
     *
     * @param [type] $url
     * @param array $parm
     * @param boolean $isPOST
     * @return void
     */
    public function queryURL($url, $param=array(), $isPOST=false){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if($isPOST) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        }else if(!empty($param)) {
            $xi = parse_url($url);
            $url .= empty($xi['query']) ? '?' : '&';
            $url .= http_build_query($param);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        if($result == false) {
            return view('errors.error403');
        }
        curl_close($ch);
        
        return json_decode($result, true);
    }

    /**
     * 获取code
     *
     * @return void
     */
    public function getCode(){
        $query = http_build_query(array(
            'client_id'		=> $this->config['AppID'],
            'redirect_uri'	=> self::CODE_REDIRECT,
            'state'			=> 'QUERY'
        ));
        $url = self::OAUTH_CODE.'?'.$query;
        return redirect($url);
    }
    
    /**
     * 获取token
     *
     * @param Request $request
     * @return void
     */
    public function getToken(Request $request){
        if(!empty($this->config['info']) && !isset($this->config['info']['access_token'])){
            $error = '授权失败，请重新授权';
            return view('welcome', compact('error'));
        }

        $code = $request->input('code');
        $this->config['code'] = $code;
        if(!empty($code)){
            $url = self::OAUTH_TOKEN;
            $param = array(
				'client_id'		=> $this->config['AppID'],
				'client_secret'	=> $this->config['AppSecret'],
				'code'			=> $this->config['code'],
				'redirect_uri'	=> self::TOKEN_REDIRECT,
			);
            $this->config['info'] = $this->queryURL($url, $param, TRUE);
            if(!isset($this->config['info']['access_token'])){
                $error = '授权失败，请重新授权';
                return view('welcome', compact('error'));
            }
            session(['token'=>$this->config['info']['access_token']]);
            $this->getUser();
            $user = $this->config['user'];
            return view('index', compact('user'));
        }
        
        $error = '授权失败，请重新授权';
        return view('welcome', compact('error'));
    }

}
