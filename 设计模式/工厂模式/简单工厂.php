<!--简单工厂模式：-->
<!---->
<!--1. 定义：用来实现创建对象和对象的使用分离，将对象的创建交给专门的工厂类负责。-->
<!--2. 举例说明：我有一个工厂类，我们有 A 产品类生产 A 产品和 B 产品类生产 B 产品或更多的产品类，-->
<!--   你下单什么产品，工厂就调用指定的产品类进行生产产品。-->
<!--3. 代码说明：-->

<?php
//简单工厂模式 2020年09月21日21:26:50
class ProductA
{
    function __construct()
    {
        echo "I am ProductA class <br>";
    }
}
class ProductB
{
    function __construct()
    {
        echo "I am ProductB class <br>";
    }
}
class Factory
{
    public static function CreateProduct($name){
        if ($name == 'A') {
            return new ProductA();
        } elseif ($name == 'B') {
            return new ProductB();
        }
    }
}
$cat = Factory::CreateProduct('A');
$dog = Factory::CreateProduct('B');

