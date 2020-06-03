<?php
error_reporting(0);
$redisHost="192.168.5.114";
$redisPort="6379";
$redis = new Redis();
$redis->connect($redisHost,$redisPort);
include_once("RedisSession.php");
$redisSession=new RedisSession($redis);
/*
$redisSession->set("name","sdf4");
$redisSession->set("age",1234);
$redisSession->set("***","man14");
$redisSession->set("name","abc4");
$redisSession->setMutil(array("province"=>"guangdong","city"=>"guangzhou"));
*/

$redisSession->setObject("obj",array("test1"=>array("test2")));
$obj=$redisSession->getObject("obj");
print_r($obj);
die();
print_r($redisSession->getAll());
//$redisSession->del("name");
print_r($redisSession->get("name"));
//print_r($redisSession->get("province"));
//$redisSession->delAll();
//print_r($redisSession->getAll());
print_r($redisSession->getFromCache("name"));
/*
$redisSession->del("name");
$redisSession->delAll();
*/