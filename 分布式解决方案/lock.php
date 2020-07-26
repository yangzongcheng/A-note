<?php
$redis = new Redis();
$redis->connect("127.0.0.1", 6379);

getLock($redis);

function  getLock($redis){
    $key = "lock";
    $value = time().mt_rand(1,99999999).mt_rand(1,9999999999999);
    $expire = 100;

    $status =true;
    while($status){
        // 参数解释 ↓
// $value 加锁的客户端请求标识, 必须保证在所有获取锁清秋的客户端里保持唯一, 满足上面的第3个条件: 加锁/解锁的是同一客户端
// "NX" 仅在key不存在时加锁, 满足条件1: 互斥型
// "EX" 设置锁过期时间, 满足条件2: 避免死锁
        if($redis->set($key, $value, ["NX", "EX" => $expire])){
            //获取锁成功

            $msg =  "获取锁成功{$value} ----";
//    sleep(1);

//    for ($i=0;$i<100;$i++){
//        $v = $i;
//    }

            $identification = $value;
// KEYS 和 ARGV 是lua脚本中的全局变量
            $script = <<< EOF
if redis.call("get", KEYS[1]) == ARGV[1] then
    return redis.call("del", KEYS[1])
else
    return 0
end
EOF;
# $result = $redis->eval($script, [$key, $identification], 1);
// 返回结果 >0 表示解锁成功
// php中参数的传递顺序与标准不一样, 注意区分
// 第2个参数表示传入的 KEYS 和 ARGV, 通过第3个参数来区分, KEYS 在前, ARGV 在后
// 第3个参数表示传入的 KEYS 的个数
            $result = $redis->eval($script, [$key, $identification], 1);
            if($result){
                $msg .=  "释放锁成功{$value}\n";
            }else{
                $msg .= "释放锁失败{$value}\n";
            }
            echo $msg;

            file_put_contents('ok.log',$msg,FILE_APPEND);
            $status = false;

        }else{
            //获取锁失败
            $msg =  "获取锁失败,排队{$value} \n";
            echo $msg;
            file_put_contents('field.log',$msg,FILE_APPEND);

        }
    }
}