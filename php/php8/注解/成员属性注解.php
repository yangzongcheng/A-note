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
 * 属性注解
 * DateTime:  2022/11/10 11:42:49
 * ClassName: Meth
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class PropertyArr{
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
        $classPro = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
        $proArr = [];
        foreach ($classPro as $itemMet)
        {
            $attributes = $itemMet->getAttributes(PropertyArr::class)[0];
            $attr = $attributes->newInstance();
            $proArr[] =[
                'pro'=>$itemMet->name,
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
            'pro'=>$proArr
        ];
        return $arr;
    }
}


#[Myattr('class A','测试属性注解',123123)]
class A extends DataAbstract {


    public function testA():bool
    {
        $data = $this->getData();
        print_r($data);
        return true;
    }

   #[PropertyArr('属性userName','备注userName')]
   public string $userName;


    #[PropertyArr('属性userAccount','备注userAccount')]
    public string $userAccount;

    #[PropertyArr('属性phone','备注phone')]
    public string $phone;



}



$data = (new A())->testA();




