<?php
$redis = new Redis();
$redis->connect("127.0.0.1", 6379);

getLock($redis);

//redis 实现分布式锁

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


//使用Lua脚本的原因:
//
//避免误删其他客户端加的锁
//eg. 某个客户端获取锁后做其他操作过久导致锁被自动释放, 这时候要避免这个客户端删除已经被其他客户端获取的锁, 这就用到了锁的标识.
//lua 脚本中执行 get 和 del 是原子性的, 整个lua脚本会被当做一条命令来执行
//即使 get 后锁刚好过期, 此时也不会被其他客户端加锁
//eval命令执行Lua代码的时候，Lua代码将被当成一个命令去执行，并且直到eval命令执行完成，Redis才会执行其他命令。
//由于 script 执行的原子性, 所以不要在script中执行过长开销的程序，否则会验证影响其它请求的执行。
//
//解锁容易错误的点:
//
//直接 del 删除键
//原因: 可能移除掉其他客户端加的锁(在自己的锁已过期情况下)
//get判断锁归属, 若符合再 del
//原因: 非原子性操作, 若在 get 后锁过期了, 此时别的客户端进行加锁操作, 这里的 del 就会错误的将其他客户端加的锁解开.
//
//
//作者：嘉兴ing
//链接：https://segmentfault.com/a/1190000019138071
//来源：SegmentFault 思否
//著作权归作者所有。商业转载请联系作者获得授权，非商业转载请注明出处。

