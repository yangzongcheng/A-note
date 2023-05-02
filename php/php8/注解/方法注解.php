<?php

/**
 * 类解类
 * DateTime:  2022/9/22 14:15:06
 * ClassName: Myattr
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Myattr{
    public string $name;
    public mixed $value;
    public int $number;

    public function __construct($name,$value,$number){
        $this->name=$name;
        $this->value=$value;
        $this->number=$number;
    }

    public function say(){
        echo "{$this->name}--{$this->value}  --  {$this->number}";
    }
}

/**
 * 方法注解
 * DateTime:  2022/11/10 11:42:49
 * ClassName: Meth
 */
#[Attribute(Attribute::TARGET_METHOD)]
class MethodArr{
 public string $name;
 public string $desc;
    public function __construct(string $name, string $desc){
        $this->name=$name;
        $this->desc=$desc;
    }
}


/**
 * 抽象
 * DateTime:  2022/9/22 14:26:44
 * ClassName: DataAbstract
 */
abstract class DataAbstract
{
    protected function getData():array
    {

        #ReflectionFunction 函数注解
        $ref=new ReflectionClass(static::class);

        #处理类注解
        $attr=$ref->getAttributes(Myattr::class)[0];
        $obj=$attr->newInstance();

        #处理方法注解
        $classMeth = $ref->getMethods(ReflectionMethod::IS_PUBLIC);
        $funcArr = [];
        foreach ($classMeth as $itemMet)
        {
            $attributes = $itemMet->getAttributes(MethodArr::class)[0];
            $attr = $attributes->newInstance();
            $funcArr[] =[
                'func'=>$itemMet->name,
                'arg'=>[
                    'name'=>$attr->name,
                    'desc'=>$attr->desc
                ]
            ];
        }
        $arr =[
            'name'=>$obj->name,
            'value'=>$obj->value,
            'number'=>$obj->number,
            'func'=>$funcArr
        ];
        return $arr;
    }
}


#[Myattr('action testA','测试方法注解',123123)]
class A extends DataAbstract {

    #[MethodArr('action testA','测试函数注解A')]
    public function testA():bool
    {
     $data = $this->getData();
     print_r($data);
     return true;
    }

    #[MethodArr('action testB','测试函数注解B')]
    public function testB():bool
    {
        return true;
    }

    #[MethodArr('action testC','测试函数注解C')]
    public function testC():bool
    {
        return true;
    }



}



$data = (new A())->testA();




