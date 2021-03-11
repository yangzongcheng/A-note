<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2018/3/15
 * Time: 14:00
 */
//php中rsa加解密实现：

//首先要生成一对公钥私钥。前提是linux机器上安装了openssl命令。
//生成私钥文件：openssl genrsa -out rsa_private_key.pem 1024
//利用私钥，生成公钥： openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem

/***
 * 原理：生成一个 私钥文件和公钥文件
 * 加解密：用公钥加密用私钥解密 反之用私钥加密用公钥解密
 * 签名验证：签名是不可逆(签名后不能解密)的所以只能验证码,用私钥加密 用公钥验证
 * 应用领域：api接口加密安全度更高
 *
 *
 */
header('Content-Type:text/html;Charset=utf-8;');
include_once "RSA.php";


$rsa = new RSA();
$rst = array(
    'ret' => 200,
    'code' => 1,
    'data' => array(1, 2, 3, 4, 5, 6),
    'msg' => "success",
);

$ex = json_encode($rst);

echo "加密前:".$ex;
echo "<br>";


//加密
$ret_e = $rsa->encrypt($ex);

echo "加密后:".$ret_e;
echo "<br>";
//die;



//解密
$ret_d = $rsa->decrypt($ret_e);

echo "解密后:".$ret_d;
echo "<br>";
echo "<br>";
echo "<br>";



$a = 'test';
//签名
$x = $rsa->sign($a);
echo "签名:".$x;

echo "<br>";
echo "<br>";


//验证
$y = $rsa->verify('test', $x);
if($y){
    echo '验证成功';
}else{
    echo '验证失败';
}

exit;