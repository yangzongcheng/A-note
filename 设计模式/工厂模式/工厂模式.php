<!--工厂模式：-->
<!--1. 定义：在该模式中，核心的工厂类不再负责所有产品的创建，而是将具体创建工作交给子类去做。-->
<!--    这个核心类仅仅负责给出具体工厂必须实现的接口，而不负责产品类被实例化这种细节。-->
<!--2. 举例说明：我有一个工厂总部（核心）和一个产品总部（核心），工厂总部负责管工厂，产品总部负责管产品，-->
<!--    产品总部下有多少个产品，工厂总部下就有多少个工厂，即指定的工厂生产指定的产品（一对一关系）；-->
<!--    如果你下单 A 产品，A 工厂就生产产品；你下单 B 产品，B 工厂就生产产品。-->
<!--3. 代码说明：-->
<?php
interface Product{     //产品接口（核心类）
    public function getProduct();
}

class ProductA implements Product   //A类产品
{
    public function getProduct(){
        echo "I'm ProductA <br>";
    }
}
class ProductB implements Product   //B类产品
  {
      public function getProduct(){
      echo "I'm ProductB <br>";
    }
  }
abstract class Factory{      //抽象工厂类（抽象类）（核心工厂类）
    abstract static function createProduct();
}
  class ProductAFactory extends Factory   //A工厂继承工厂类
  {
      public static function createProduct()
      {
          return new ProductA();   //A工厂创建A产品类对象
      }
  }
  class ProductBFactory extends Factory  //B工厂继承工厂类
  {
      public static function createProduct()
      {
          return new ProductB();    //B工厂创建B产品类对象
      }
  }
//调用：
//注意：声明为static的静态方法不能通过new实例化对象进行访问，必须使用类名::方法名() 访问
  $ProductA = ProductAFactory::createProduct();
  $ProductA->getProduct();
  $ProductB = ProductBFactory::createProduct();
  $ProductB->getProduct();

