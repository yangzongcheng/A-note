<?php

include_once('Parenter.php');

class Consumer extends Parenter
{
    public function __construct()
    {
        parent::__construct('exchange', 'bet_order_list_queue', 'routeKey');
    }

    public function doProcess($msg,$msgObj)
    {
//        var_dump($msgObj);
//        sleep(1);
//        echo date('Y-m-d H:i:s').'---- 队列名：'.$this->queueName.'-----:'.$msg . "\n";
       file_put_contents('./1.log',print_r($msgObj,true));
        print_r($msg);
        echo "\n\n";
    }
}

$consumer = new Consumer();
//$consumer->dealMq(false);
$consumer->dealMq(true);

