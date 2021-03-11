<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2018/4/11
 * Time: 09:05
 */

include_once "Aes.php";
use Aes\Aes;

/**
 * [token 获取token]
 * @Author    ZiShang520@gmail.com
 * @DateTime  2017-10-16T10:32:32+0800
 * @copyright (c)                      ZiShang520 All           Rights Reserved
 * @param     [type]                   $data      [description]
 * @return    [type]                              [description]
 */


class Token{

    //3000s 后过期
    static $old_time = 3000;
    /**
     * @param $data
     * @return string
     * 加密
     */
   static  function make_token($data)
    {

        return Aes::url_encode(Aes::encrypt(json_encode($data)));
    }




    /**
     * @param $token
     * @return bool
     * 验证
     */
   static  function check($token)
    {

        if (($user = Aes::decrypt(Aes::url_decode($token))) !== false) {
            $user_data = json_decode($user);
            if(time()-$user_data->now_time >= self::$old_time){
                //登录已过期
                return false;
            }
            return $user_data;
        }
        return false;
    }

}



$data = ['a','b','c','name'=>'张三','sex'=>'男','now_time'=>time(),'b123'=>'qweqweqweqw'];





$obj = new Token();

//加密
$token  = $obj->make_token($data);

echo $token.'<br>';

//验证
$check  = $obj->check($token);


//登录过期设置每次登录存当前时间，设置一个过期时间段  每次请求的时候根据当前时间和token取出来的时间作比较超过设置的时间 则过期
$time = $check->now_time;

echo $time;

echo $check->sex;

print_r($check);
