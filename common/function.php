<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2017/7/28
 * Time: 9:40
 * 公共函数库
 */


/**
 * @param $key
 * @param $val
 * 设置 js localStorageSet
 */
function localStorageSet($key,$val){
    echo "<script> 
     localStorage.setItem('{$key}','{$val}');
  </script>";
}

 /***
     * @param $value
     * @return int
     * 获取数字小数位数
     */
    function getFloatLength($value){
        if($value){
            $value = floatval($value);
            $arr = explode('.',$value);
            if(!empty($arr[1])){
                return strlen($arr[1]);
            }
        }
        return 0;
    }


/**
 * @param $pwd
 * 验证密码规则
 * 6-16 字母数字
 */
function preg_pwd($pwd)
{
    if(!preg_match('/^[0-9a-zA-Z_#.+*,?.;]{6,16}$/',$pwd))
    {
        return false;
    }
    return $pwd;
}

/**
 * @param string $key or array
 * @param string $msg
 * 接受强制结束参数
 */
function exit_msg($key='id',$msg='恶意操作'){
    if(is_array($key)){

        $arr = [];
        foreach ($key as $k=>$v){
            $arr[$k] = getRequest($k)?:exit($msg.' not param '.$k);
        }
        return $arr;
    }else{
        return getRequest($key)?:exit($msg.' not param '.$key);
    }

}

/**
 * @param $param
 * 验证参数
 */
function checkRequest($param){
    if(is_array($param)){
        $arr =[];
        foreach ($param as $key=>$val){
            if(!isset($_REQUEST[$key]) || !trim($_REQUEST[$key])){
                if($val){
                    $msg = $val;
                }else{
                    $msg = 'not '.$key;
                }
                return jsonReturn(201,$msg);
            }
            $arr[$key] = trim($_REQUEST[$key]);
        }

        return $arr;
    }else{
        return jsonReturn(201,'参数错误');
    }
}

if(!function_exists('getRequest')){
    /**
     * @param $key
     * @return bool|string
     * 获取 $_REQUEST 参数
     */
    function getRequest($key,$default=false){
        if(isset($_REQUEST[$key])){
            if(!is_array($_REQUEST[$key])){
                return trim($_REQUEST[$key]);
            }else{
                return $_REQUEST[$key];
            }

        }else{
            return $default;
        }
    }
}

/**
 * json返回
 * 封装中文转义
 * 防止中文乱码s
 * 先在配置文件里获取配置参数
 */
function jsonReturn($code,$msg='',$data=[]){

    $config = config('params');
    $re = ['error'=>'code 无次配置项'];
    foreach ($config as $key=>$val){
        if($val['code']==$code){
            $re = $val;
            $re['msg'] = $msg?:$val['msg'];
            $re['data'] = $data;
            break;
        }
    }

    return die(json_encode($re,JSON_UNESCAPED_UNICODE));

}





if(!function_exists('str_explode')){
    /**
     * @param $str
     * @return array
     * 自定义字符串截取
     */
    function str_explode($str,$at='_')
    {
        if (strpos($str, $at) !== false) {
            return explode($at, $str);
        }else{
            return [$str];
        }
    }
}




/**
 * @param $tel
 * @return string
 * 正则验证手机号码
 */
function checkTel($tel){
    if(!preg_match('/^1[3|4|5|7|8]\d{9}$/',trim($tel))){
        jsonReturn(201,'手机号码格式错误');
    }
    return trim($tel);
}
/**
 * 生成UUID
 * @param string $prefix 可以指定前缀
 * @return string
 */
function create_uuid($prefix = "") {
    $str = md5(uniqid(mt_rand(), true));
    $uuid  = substr($str,0,8) . '_';
    $uuid .= substr($str,8,4) . '_';
    $uuid .= substr($str,12,4) . '_';
    $uuid .= substr($str,16,4) . '_';
    $uuid .= substr($str,20,12);
    return $prefix . $uuid;
}

if(!function_exists('file_log')){
    /**
     * @param $path
     * @param $content
     * 文件日志
     */
    function file_log($path,$content){
        file_put_contents($path,$content,FILE_APPEND);
    }
}

if(!function_exists('dump_r')){
    /**
     * @param $data
     * 打印
     */
  function dump_r($data){
        echo "<pre>".print_r($data,true);die;
    }
}


if(!function_exists('curl_return_code')){
    /**
     *返回状态吗
     * @param $url
     * @return mixed
     */
    function curl_return_code($url){
        $ch = curl_init ();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
         #curl_setopt( $ch, CURLOPT_POSTFIELDS, "username=".$username."&password=".$password );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_exec($ch);
        return curl_getinfo($ch,CURLINFO_HTTP_CODE);
    }
}

if(!function_exists('http_curl')){
    /**
     * @param $url
     * @param string $data
     * @return mixed
     * curl模拟post请求
     */
    function http_curl($url,$data='',$method='get')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($method=='post'){
            // post数据
            curl_setopt($ch, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $rust = curl_exec($ch);
        if (false === $rust) {
            return curl_error($ch);
        }
        curl_close($ch);
        //打印获得的数据
        return $rust;
    }
}


/**
 * @param $data
 * 打印
 */
function dd($data){
    echo "<pre>".print_r($data,true);die;
}
if(!function_exists('curlXml')){
    /**
     * @param $url
     * @param $sml
     * @return mixed
     * curl 模拟xml数据提交
     */
    function curlXml($url,$xml){
        $ch = curl_init();
        $header[] = "Content-type: text/xml";//定义content-type为xml
        curl_setopt($ch, CURLOPT_URL, $url); //定义表单提交地址
        curl_setopt($ch, CURLOPT_POST, 1);   //定义提交类型 1：POST ；0：GET
        curl_setopt($ch, CURLOPT_HEADER, 1); //定义是否显示状态头 1：显示 ； 0：不显示
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//定义请求类型
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);//定义是否直接输出返回流
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml); //定义提交的数据，这里是XML文件
        $result = curl_exec($ch);
        curl_close($ch);//关闭
        return $result;
    }
}

//公共方法
if(!function_exists('trim_all')){
    /**
     * @param $str
     * @return mixed
     * 去掉前后中间的空格
     */
    function trim_all($str){
        return  preg_replace('# #','',$str);
    }
}

if(!function_exists('get_ip')){
    /**
     * @return string
     * 获取ip地址
     */
    function get_ip()
    {
        if(isset($_SERVER['HTTP_X_REAL_IP']) && $_SERVER['HTTP_X_REAL_IP']<>'')
        {
            $onlineip = htmlentities($_SERVER['HTTP_X_REAL_IP']);
        }else if(isset($_SERVER['REMOTE_ADDR'])){
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }else{
            $onlineip = '127.0.0.1';
        }
        return $onlineip;
    }

}

if(!function_exists('get_city')){
    /**
     * 获取 IP  地理位置
     * 淘宝IP接口
     * @Return: array
     */
    function get_city($ip = '')
    {
        if($ip == ''){
            $url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json";
            $ip=json_decode(file_get_contents($url),true);
            $data = $ip;
        }else{
            $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
            $ip=json_decode(file_get_contents($url));
            if((string)$ip->code=='1'){
                return false;
            }
            $data = (array)$ip->data;
        }

        return $data?$data['country'].$data['country_id'].$data['area'].$data['region'].$data['city'].$data['isp']:'';
    }
}

if(!function_exists('get_http')){
    /**
     * @return string
     * 获取当前域名
     */
    /**
     * @return string
     * 获取当前域名
     */
    function get_http()
    {
        $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
        if($_SERVER['SERVER_NAME']!='_'){
            $http = $http . $_SERVER['SERVER_NAME'];
        }else{
            $http = $http . $_SERVER['HTTP_HOST'];
        }
        $port = $_SERVER["SERVER_PORT"] == 80 ? '' : ':' . $_SERVER["SERVER_PORT"];
        $url = $http . $port;
        $host = $url . '/';
        return $host;
    }

}

/**
 * @param $tel
 * @return string
 * 验证手机号码
 */
function check_tel($tel){
    if(!preg_match('/^1[3|4|5|6|7|8]\d{9}$/',trim($tel))){
        return false;
    }
    return trim($tel);
}

if(!function_exists('make_pwd')){

    /**
     * @param $password
     * @param $salt
     * @return string
     * 密码加密
     */
    function make_pwd($password,$salt=''){
        if(trim($password) && $salt){
            return sha1(md5($password.$salt));
        }else{
            exit('error');
        }

    }

}

if(!function_exists('preg_pwd')){
    /**
     * @param $pwd
     * 验证密码规则
     * 6-16 字母数字
     */
    function preg_pwd($pwd)
    {
        if(!preg_match('/^[0-9a-zA-Z_#.+*,?.;]{6,16}$/',$pwd))
        {
            return false;
        }
        return $pwd;
    }
}



if(!function_exists('two_number')){
    /**
     * @param $val
     * @return string
     * 把结果保留两位小数
     */
    function retainNumber($val,$digit=2){
        return sprintf("%.{$digit}f",$val);
    }
}

/**
 * 请求日志
 */

function requestLog($return){

    $dir = APP_PATH.'Runtime/log_api/'.MODULE_NAME.'/'.date('Y_m_d').'/';
    if(!is_dir($dir))
    {
        mkdir($dir,0777,true);
    }
//        $request = print_r($_REQUEST,true);
    $request = 'GET:'.print_r($_GET,true)."\nPOST:".print_r($_POST,true)."\n";
    if($_FILES){
        $request = "file:\n".print_r($_FILES,true)."\n\n".$request;
    }
    $type = $_SERVER['REQUEST_METHOD'];
    $return = print_r($return,true);
    $ip = get_ip();
    //最后执行的sql语句
    $sql = M()->getLastSql();

    $con = "请求来源:".get_os()."\n请求ip:".$ip."\n请求方式:".$type."\n请求参数:\n".$request."\nsql:".$sql."\n"."返回值:\n".$return."\n\n\n\n\n\n";
    file_put_contents($dir.CONTROLLER_NAME.'_'.ACTION_NAME.'.log','请求时间:'.date('Y-m-d H:i:s',time())."\n".$con."\n",FILE_APPEND);
}


/**
 * 获取客户端操作系统信息包括win10
 * @param  null
 * @author  Jea杨
 * @return string
 */
function get_os(){
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent))
    {
        $os = 'Windows 7';
    }
    else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent))
    {
        $os = 'Windows 8';
    }else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent))
    {
        $os = 'Windows 10';#添加win10判断
    }else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent))
    {
        $os = 'Windows XP';
    }
    else if (preg_match('/linux/i', $agent))
    {
        $os = 'Linux';
    }
    else if (preg_match('/unix/i', $agent))
    {
        $os = 'Unix';
    }
    else if(strpos($agent, 'iphone') || strpos($agent, 'ipad')){
        $os = 'ios';
    } else if(strpos($agent, 'android')){
        $os = 'android';
    }else if(strpos($agent, 'mac')){
        $os = '苹果mac';
    }else{
        $os = '未知操作系统';
    }


    return $os;
}


/**
 * 通用正则库
 * $arr 格式如 ['password'=>['val'=>'123456','msg'=>'密码格式错误','patt'=>'/[a-zA-Z\d_-=+.]{6,16}/']];
 */
function check_preg($arr){
    $rust = [
        'mobile'=>['patt'=>'/1[35789]{1}\d{9}/','msg'=>'手机号码格式错误'],//手机号
        'email'=>['patt'=>'/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/','msg'=>'邮箱格式错误'],//邮箱
        'username'=>['patt'=>'/[a-zA-Z\d_-]{4,16}$/','msg'=>'用户名为4-16位 字母加数字加_(下划线)加减号'],//用户名
        'password'=>['patt'=>'/^[0-9a-zA-Z_#.+*,?.;]{6,16}$/','msg'=>'密码为6-16位字母加数字加字符(_#.+*,?.;)'],//密码
    ];
    if(is_array($arr)){

        foreach ($arr as $key=>$val){
            //判断值
            !isset($val['val'])?jsonReturn(201,$key.' 验证参数的字段值没有val 的下标(key)'):'';
            !$val['val']?jsonReturn(201,'$key.\' 验证参数的字段值val 不能为空'):'';

            //判断正则库 这个key 是否存在
            if(array_key_exists($key,$rust)){
                //判断 值是否是一个数组
                if(is_array($val)){
                    //判断是否包含正则表达式
                    $errmsg = isset($val['msg']) && $val['msg']?$val['msg']:$rust[$key]['msg'];
                    if(isset($val['patt']) && $val['patt']){
                        //包含
                        if(!preg_match($val['patt'],$val['val']))
                        {
                            return  jsonReturn(201,$errmsg);
                        }
                    }else{

                        //不包含使用正则库的正则表达式
                        if(!preg_match($rust[$key]['patt'],$val['val']))
                        {
                            return  jsonReturn(201,$errmsg);
                        }
                    }

                }else{
                    return jsonReturn(201,$key.' 参数字段错误,需要验证的字段对象为一个数组');
                }


            }else{
                //如果正则库没有 就 判断传过来的值是否包含了正则表达时


                if(isset($val['patt']) && $val['patt']){

                    //验证msg
                    !isset($val['msg']) || !$val['msg']?jsonReturn(201,$key.' 参数字段验证值不包含在正则库,请在字段值的数组对象中添加(msg字段)提示信息'):'';
                    //包含
                    if(!preg_match($val['patt'],$val['val'])){
                        return  jsonReturn(201,$val['msg']);

                    }
                }else{
                    return jsonReturn(201,$key.' 参数字段验证值不包含在正则库,请在字段值的数组对象中添加(patt字段)正则表达式');
                }

            }

        }
    }else{
        return jsonReturn(201,'参数为一个数组对象');
    }
    return true;

}

/**
 * 自定义日志
 */
function set_log($msg){

    $dir = APP_PATH.'Runtime/log_api/'.date('Y_m_d').'/'.MODULE_NAME.'/';
    if(!is_dir($dir))
    {
        mkdir($dir,0777,true);
    }


    file_put_contents($dir.date('Y_m_d_H_i').'.log','请求时间:'.date('Y-m-d H:i:s',time())."\n".$msg."\n\n\n\n",FILE_APPEND);
}

/**
 * @param $len
 * @param null $chars
 * @return string
 * 随机生成字母数字组合字符串
 */
function getRandomString($len, $chars=null)
{
    if (is_null($chars)) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    }
    mt_srand(10000000*(double)microtime());
    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {
        $str .= $chars[mt_rand(0, $lc)];
    }
    return $str;
}