<?php
/**
 * 服务容器
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
        echo "send email";
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
        echo "send sms";
        return 'seed sms';
        // TODO: Implement seed() method.
    }

}


/**
 * 这是一个简单的服务容器
 * Class Container
 */
class Container
{
    protected $binds;

    protected $instances;

    public function bind($abstract, $concrete)
    {
        if ($concrete instanceof Closure) {
            $this->binds[$abstract] = $concrete;
        } else {
            $this->instances[$abstract] = $concrete;
        }
    }

    public function make($abstract, $parameters = [])
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        array_unshift($parameters, $this);
        return call_user_func_array($this->binds[$abstract], $parameters);
    }
}

//创建一个消息工厂
$message = new  Container();
//将发送短信注册绑定到工厂里面
$message->bind('SMS',function (){
    return   new  SeedSMS();
});
//将发送邮件注册绑定到工厂
$message->bind('EMAIL',function (){
    return new  SeedEmail();
});
//需要发送短信的时候
$SMS  = $message->make('EMAIL');
$SMS->seed();