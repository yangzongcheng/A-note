<?php
$redis = new Redis();
// 第一个参数为redis服务器的ip,第二个为端口
$res = $redis->connect('127.0.0.1', 6379);
// test为发布的频道名称,hello,world为发布的消息

for ($i=0;$i<10;$i++){
    $res = $redis->publish('test1','hello123123,world'.date('Y-m-d H:i:s'))."\n\n\n";
}




