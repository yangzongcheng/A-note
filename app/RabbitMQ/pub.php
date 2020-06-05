<?php
include_once('Publisher.php');
$publisher = new Publisher();
//生产
for ($i=1;$i<=10000;$i++){


    echo $i."推送队列名:".$publisher->queueName."\n";
    $data = json_encode(['id'=>$i,'time'=>time(),'uid'=>mt_rand(1,9999999)]);
    $publisher->sendMessage($data);

}
$publisher->closeConnetct();


