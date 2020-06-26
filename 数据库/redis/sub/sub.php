<?php
//订阅端

set_time_limit(0);
//连接本地的 Redis 服务
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

//订阅 test test1两个频道
$redis->subscribe(array('test','test1'), 'callback');

// 回调函数,这里写处理逻辑
function callback($instance, $channelName, $message) {
    echo $channelName, "==>", $message,PHP_EOL,"\n\n";
}