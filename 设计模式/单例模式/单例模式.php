<!--单例模式-->
<!---->
<!--1. 定义：保证一个类仅有一个实例，并提供一个访问它的全局访问点。-->
<!--2. 举例说明：Windows 是多进程多线程的，在操作一个文件的时候，-->
<!--    就不可避免地出现多个进程或线程同时操作一个文件的现象，所以所有文件的处理必须通过唯一的实例来进行。-->
<!--    或者一些设备管理器常常设计为单例模式，比如一个电脑有两台打印机，在输出的时候就要处理不能两台打印机打印同一个文件。-->
<!--3. 代码说明：-->


<?php
  class Singleton
  {
      private static $instance;
      //私有构造方法，禁止使用new创建对象
      private function __construct(){}
      //实例化化对象
      public static function getInstance(){
          if (!isset(self::$instance)) {   //判断是否实例化
              self::$instance = new self;
          }
          return self::$instance;
      }
      //将克隆方法设为私有，禁止克隆对象
      private function __clone(){}

      public function operation()
      {
          echo "这里可以添加其他方法和操作 <br>";
      }
  }

  $shiyanlou = Singleton::getInstance();
  $shiyanlou->operation();
//这里多补充一点单例模式的特点：
//（1）单例类只能有一个实例。
//（2）单例类必须自己创建自己的唯一实例。
//（3）单例类的构造函数定义为 private 私有方法。
//（4）单例类必须自行向整个系统提供这个实例。

