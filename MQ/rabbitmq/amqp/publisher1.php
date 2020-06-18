<?php
/**
 * Created by PhpStorm.
 * User: jmsite.cn
 * Date: 2019/1/15
 * Time: 13:15
 */

$config = array(
    'host' => '127.0.0.1',
    'vhost' => '/',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest'
);
$cnn = new AMQPConnection($config);
if (!$cnn->connect()) {
    echo "Cannot connect to the broker";
    exit();
}
$ch = new AMQPChannel($cnn);//通道交换器
$ex = new AMQPExchange($ch);//交换器
//消息的路由键，一定要和消费者端一致
$routingKey = 'key_1';
//交换机名称，一定要和消费者端一致，
$exchangeName = 'exchange_1';

$routingKey1 = 'key_2';
$exchangeName1 = 'exchange_2';

$ex1 = $ex;

$ex->setName($exchangeName);
$ex->setType(AMQP_EX_TYPE_DIRECT);
$ex->setFlags(AMQP_DURABLE);
$ex->declareExchange();

$ex1->setName($exchangeName1);
$ex1->setType(AMQP_EX_TYPE_DIRECT);
$ex1->setFlags(AMQP_DURABLE);
$ex1->declareExchange();

//创建10个消息
for ($i=1;$i<=10;$i++) {
    //消息内容
    $msg = array(
        'data' => 'message_' . $i,
        'hello' => 'world-'.date('Y-m-d H:i:s'),
    );

    $msg1 = array(
        'data' => 'message_' . $i,
        'hello' => 'ex1-'.date('Y-m-d H:i:s'),
    );


    //发送消息到交换机，并返回发送结果
    //delivery_mode:2声明消息持久，持久的队列+持久的消息在RabbitMQ重启后才不会丢失
    echo "Send Message:" . $ex->publish(json_encode($msg), $routingKey, AMQP_NOPARAM, array('delivery_mode' => 2)) . "\n";

    echo 'ex1'.$ex1->publish(json_encode($msg1), $routingKey1, AMQP_NOPARAM, array('delivery_mode' => 2)) . "\n";
    //代码执行完毕后进程会自动退出
}