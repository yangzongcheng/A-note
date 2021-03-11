<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2018/4/9
 * Time: 16:46
 */
include_once 'Lock.php';
use luoyy\IO\Lock;
class  Index{
    function asyncWork(){
        $a = mt_rand(1,10000);
        $b = mt_rand(1,55555);
        //异步锁
        Lock::asyncWork(function() use($a){

            for ($i=1;$i<10;$i++){
                sleep(1);
                echo date('Y-m-d H:i:s').$a."<br>";
            }

            return 123;
        },function()use($b){
            echo '排队'.$b;
        });
        return 0;

    }

    function work(){
        Lock::work(function (){
            echo 123;
        });
    }



}

$obj = new Index();
$obj->asyncWork();

//$obj->work();



