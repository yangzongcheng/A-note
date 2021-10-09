<?php

/**
 * 参考：依赖注入Container
 * https://zhuanlan.zhihu.com/p/97343710
 */

/**
 * 为了约束我们先定义一个消息接口
 * Interface Message
 */
interface  Message{

    public function seed();
}

/**
 * 有一个发送邮件的类
 * Class SeedEmail
 */
class SeedEmail implements Message
{

    public function seed()
    {

        return  'seed email';

        // TODO: Implement seed() method.
    }

}

/**
 *新增一个发送短信的类
 * Class SeedSMS
 */
class SeedSMS implements Message
{
    public function seed()
    {
        return 'seed sms';
        // TODO: Implement seed() method.
    }


}
/*
 * 订单产生的时候 需要发送消息
 */
class Order{

    protected $messager = '';

    function __construct(Message $message)
    {
        $this->messager = $message;

    }
    public function seed_msg()
    {
        return $this->messager->seed();
    }
}
//我们需要发送邮件的时候
$message = new SeedEmail();
//将邮件发送对象作为参数传递给Order
$Order = new Order($message);
$Order->seed_msg();


//我们需要发送短信的时候 依赖注入
$message = new SeedSMS();
$Order = new Order($message);
$Order->seed_msg();