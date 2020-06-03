<?php
include_once '../func.php';
$p = $_GET['p'];

/***
 * 非阻塞模式  如果是 http 请求必须保证每次 请求的url不相同,否则会自动变成阻塞模式  可以在url加随机数实现  如果是 cli 则无所谓
 */




/**
 * 阻塞模式（后面的进程会一直等待前面的进程执行完毕）
 */
function createOrder1($p,$func){
    echoN('开始响应'.$p);
    $file = fopen(__DIR__.'/lock.txt','w+');
    //加锁
    if(flock($file,LOCK_EX)){
        //TODO 执行业务代码
        echoN("开始执行业务代码");
        for ($i=1;$i<10;$i++){
            sleep(1);
            echoN("执行中。。。。");
        }
        $func();
        echoN("执行完毕");
        flock($file,LOCK_UN);//解锁
    }else{
        echoN('lock 请稍后再试');die;

    }
    //关闭文件
    fclose($file);
}

/**
 * 非阻塞模式（只要当前文件有锁存在，那么直接返回）
 */
function createOrder2($p){
    echoN('开始响应'.$p);
    $file = fopen(__DIR__.'/lock.txt','w+');
    //加锁
    if(flock($file,LOCK_EX|LOCK_NB)){
        echoN("开始执行业务代码");
        for ($i=1;$i<10;$i++){
            sleep(1);
            echoN("执行中。。。。");
        }
        echoN("执行完毕");
        //TODO 执行业务代码
        flock($file,LOCK_UN);//解锁
    }else{
        //TODO 执行业务代码 返回系统繁忙等错误提示
        echoN('lock 请稍后再试');
    }
    //关闭文件
    fclose($file);
}


createOrder2($p,function (){
    echo 'hahah';
});