<?php
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class EltAttr{

    public function __construct(
        public string $stepType,
        public mixed $rule
    ){

    }
}

class A{

    #[EltAttr(stepType:'数据源',rule:['12312125555'])]
    const STEP_TYPE_A ='source';

    /**
     * 获取常量注解
     * DateTime: 2023/2/9 10:45:38
     * @return array
     */
    public static function  getA(){

        #ReflectionFunction 函数注解
        $ref=new ReflectionClass(self::class);

        $constant = $ref->getReflectionConstants();

        $arr = [];
        foreach ($constant as $itemMet)
        {
            $attributes = $itemMet->getAttributes(EltAttr::class);


            $attr = $attributes[0]->newInstance();
            $arr[] =[
                'constants'=>$itemMet->name,
                'arg'=>[
                    'stepType'=>$attr->stepType,
                    'rule'=>$attr->rule
                ]
            ];
        }

        print_r($arr);
        return $arr;
    }
}



A::getA();

//(new A())->getA();








