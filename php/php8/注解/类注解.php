<?php

/**
 * 注解类
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
 * 抽象
 * DateTime:  2022/9/22 14:26:44
 * ClassName: DataAbstract
 */
abstract class DataAbstract
{
    public function getData():array
    {
        #ReflectionFunction 函数注解
        $ref=new ReflectionClass(static::class);
        $attr=$ref->getAttributes(Myattr::class)[0];
        $obj=$attr->newInstance();
        $arr =[
            'name'=>$obj->name,
            'value'=>$obj->value,
            'number'=>$obj->number
        ];
        return $arr;
    }
}


#[Myattr('A','A类注解',1231)]
class A extends DataAbstract {

}

#[Myattr('B','B类注解',12333123)]
class B extends DataAbstract{

}


$data = (new A())->getData();


print_r($data);

