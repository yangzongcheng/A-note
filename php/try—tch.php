<?php

function try1(){
    try{
        //抛出异常后代码将不在往下执行
        throw new Exception('12312312313');
        echo 'oo';
    }catch (Throwable $throwable){
        echo $throwable->getMessage();
    }
}

function try2(){
    try{
        throw new Exception('wqeqwe');

    }catch (Exception $exception){
        echo $exception->getMessage();
    }
}

function try3(){
    try{
        $arr = [1,2.3];
        echo $arr[9];
        throw new cc('wqeqwe');

    }catch (Exception $exception){
        // 计算错误
        echo 'Exception';
        echo $exception->getMessage();
    }catch (Throwable $throwable){
        // 语法错误，致命错误　　
        echo 'Throwable'."\n\n";
        echo $throwable->getMessage();
    }
}

try3();