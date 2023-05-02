<?php
include_once '../func.php';
//数字
$int ='/^[0-9]*$/';


//n位的数字
$allInt = '/^\d{n}$/';

//至少n位的数字
$minInt = '/^\d{n,}$/';

//邮箱
$email ='/[\w]+(\.[\w]+)*@[\w]+(\.[\w])+/';




$test ='/\W/';






$preg =$http;
$str ='123123@qq.com';
if(preg_match($preg,$str)){
    dd('匹配成功');
}else{
    dd('匹配失败');
}
