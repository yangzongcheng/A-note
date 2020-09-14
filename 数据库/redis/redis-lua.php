<?php
$redis = new \Redis();
$redis->connect("127.0.0.1", 6379);
$script = <<<SCRIPT
local setTrue = redis.call('set',KEYS[1],ARGV[1])
local setNum = redis.call('set',KEYS[2],ARGV[2])
if(setTrue and setNum) then
local valNum = redis.call('get',KEYS[2])
-- 此处返回 KEYS[2] 的值
    return valNum
else
    return false
end
SCRIPT;

$redis->script('load', $script);
$handle = $redis->eval($script, ['cc','dd',time(),date('Y-m-d H:i:s')], 2);
//此处打印出ok
print_r($handle);