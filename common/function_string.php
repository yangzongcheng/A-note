<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2018/4/16
 * Time: 11:27
 */


/**
 * @param $str
 * @param string $delimiter
 * @return int
 * 某个字符出现的次数
 */
function strCount($str,$delimiter=''){
    $arr = explode($delimiter,$str);
    return count($arr)-1;
}

/**
 * 分割中文字符串
 * $str 字符串
 * $count 个数
 */
function mb_str_split($str,$len){
    $strCount = mb_strlen($str,"utf-8");
    $count    =ceil($strCount/$len);
    $arr = array();
    for ($i=0; $i < $strCount; $i+=$count) {
        $arr[] = mb_substr($str, $i, $count);
    }
    return $arr;
}

/**
 * 数字转字母 （类似于Excel列标）
 * @param Int $index 索引值
 * @param Int $start 字母起始值
 * @return String 返回字母
 * 数字转字母 0=A  25 = Z  26==AA
 */
function IntToChr($index, $start = 65) {
    $str = '';
    if (floor($index / 26) > 0) {
        $str .= IntToChr(floor($index / 26)-1);
    }
    return $str . chr($index % 26 + $start);
}
/***
 * @param $lenth
 * @param int $start
 * @return array
 * 根据长度把数字转字母 26  A-Z     27=AA
 * 主要用于php excel操作
 */
function letter_arr($lenth,$start=65){
    $arr = [];
    for ($i=0;$i<$lenth;$i++){
        $str = '';
        if (floor($i / 26) > 0) {
            $str .= IntToChr(floor($i / 26)-1);
        }
        $letter =  $str . chr($i % 26 + $start);
        $arr[] = $letter;
    }
    return $arr;
}

/**
 * 数据加密、解密
 *
 * @access	public
 * @param	string   $string     加密、解密字符串
 * @param	string   $operation  加密、解密操作符(ENCODE加密、DECODE解密)
 * @param	string   $key        密钥
 * @param	string   $expiry     过期时间
 * @return	string
 */
function str_encode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
    $authKey     	= md5('5b6u5L+h5bCP6L2m1h');
    $ckey_length 	= 4;
    $key         	= md5($key != '' ? $key : $authKey);
    $keya        	= md5(substr($key, 0, 16));
    $keyb       	= md5(substr($key, 16, 16));
    $keyc       	= $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    $cryptkey   	= $keya.md5($keya.$keyc);
    $key_length		= strlen($cryptkey);
    $string 		= $operation == 'DECODE' ?
        base64_decode(substr($string, $ckey_length))
        : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length 	= strlen($string);
    $result 		= '';
    $box 			= range(0, 255);

    $rndkey 		= array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        }else{
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}