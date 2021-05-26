<?php
require './vendor/autoload.php';
$host= "tcp://111.17.169.116:61613";
$host = "tcp://111.85.112.17:1194";
$stomp = new \FuseSource\Stomp\Stomp($host);
print_r($stomp);
die;
//订阅只对一个有效，如果启动多个脚本，只有一个会接收到消息
$topic = "/topic/PF.TOPIC.EVENT";
$stomp->subscribe($topic);

while(true) {
    //判断是否有读取的信息
    if($stomp->hasFrame()) {
        $frame = $stomp->readFrame();

        $data = json_decode($frame->body, true);
        var_dump($data);

        //我们通过获取的数据
        //处理相应的逻辑，比如存入数据库，发送验证码等一系列操作。
        //$db->query("insert into user values('{$username}','{$password}')");
        //sendVerify();

        //表示消息被处理掉了，ack()函数很重要
        $stomp->ack($frame);
    }
    sleep(1);
}
