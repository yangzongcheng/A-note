<?php

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class EtlStepType
{
    public function __construct(
        public string $desc,#说明
        public mixed $rule#规则demo
    ){

    }
}


/**
 * ETL同步任务
 * DateTime:  2023/1/6 10:24:25
 * ClassName: GovTaskLogModel
 * @package BigData\DataGovernance
 */
class GovEtlTaskModel
{
    const DB_NAME = 'big_data';
    const TABLE_NAME = 'gov_etl_task';

    #任务类型 1手动 2自动
    const TASK_TYPE_MANUAL = 1;
    const TASK_TYPE_AUTO = 2;
    const TASK_TYPE = [
        self::TASK_TYPE_MANUAL,
        self::TASK_TYPE_AUTO
    ];

    #运行状态 1运行 2停止
    const STATE_RUN = 1;
    const STATE_STOP = 2;
    const STATE = [
        self::STATE_RUN,
        self::STATE_STOP
    ];

    #更新周期
    #每日 ['type'=>'day,'hour'=>'08','minute'=>'45'] 每日几时几分
    const CYCLE_DAY = 'day';

    #每周 ['type'=>'week','week'=>'0','hour'=>'08','minute'=>'45']  每周周几几时几分 0为周天
    const CYCLE_WEEK = 'week';

    #每月 ['type'=>'month','day'=>'21','hour'=>'08','minute'=>'45']  每月几号几时几分
    const CYCLE_MONTH = 'month';
    const CYCLE_TYPE = [
        self::CYCLE_DAY => ['hour', 'minute'],
        self::CYCLE_WEEK => ['week', 'hour', 'minute'],
        self::CYCLE_MONTH => ['day', 'hour', 'minute']
    ];





    #支持的函数
    const FUNC_TYPE_JUHE = 'juhe';
    const FUNC_TYPE_NUNBER = 'number';
    const FUNC_TYPE_DATE = 'date';
    const FUNC_TYPE_STRING = 'string';

    const FUNC_TYPE_EXP ='exp';

    const FUNC_TYPE_NAME = [
        self::FUNC_TYPE_JUHE=>'聚合',
        self::FUNC_TYPE_NUNBER=>'数值',
        self::FUNC_TYPE_DATE=>'日期',
        self::FUNC_TYPE_STRING=>'字符',
        self::FUNC_TYPE_EXP=>'表达式函数'
    ];


    #聚合
    const FUNC_MAX ='max';
    const FUNC_MIN = 'min';
    const FUNC_AVG = 'avg';
    const FUNC_COUNT = 'count';
    const FUNC_SUM = 'sum';

    #数值
    const FUNC_ABS = 'abs';
    const FUNC_CEIL = 'ceil';
    const FUNC_FLOOR = 'floor';
    const FUNC_ROUND = 'round';

    #日期
    const FUNC_YEAR = 'year';
    const FUNC_MONTH = 'month';
    const FUNC_TODAY = 'today';
    const FUNC_OLD = 'old';#返回年龄

    #字符
    const FUNC_TRIM = 'trim';
    const FUNC_SUBSTR = 'substr';#字符串截取
    const FUNC_STRREV = 'strrev';#字符反转
    const FUNC_STRLEN = 'strlen';#字符长度
    const FUNC_INSTR = 'instr';#示例 instr(【地址】,‘四川’) 返回 地址这列存在四川的字符次数

    #if函数
    const FUNC_IF ='if';#if函数

    #函数所属类型
    const FUNC_TYPE =[
        self::FUNC_MAX=>self::FUNC_TYPE_JUHE,
        self::FUNC_MIN=>self::FUNC_TYPE_JUHE,
        self::FUNC_AVG=>self::FUNC_TYPE_JUHE,
        self::FUNC_COUNT=>self::FUNC_TYPE_JUHE,
        self::FUNC_SUM=>self::FUNC_TYPE_JUHE,

        self::FUNC_ABS=>self::FUNC_TYPE_NUNBER,
        self::FUNC_CEIL=>self::FUNC_TYPE_NUNBER,
        self::FUNC_FLOOR=>self::FUNC_TYPE_NUNBER,
        self::FUNC_ROUND=>self::FUNC_TYPE_NUNBER,

        self::FUNC_YEAR=>self::FUNC_TYPE_DATE,
        self::FUNC_MONTH=>self::FUNC_TYPE_DATE,
        self::FUNC_TODAY=>self::FUNC_TYPE_DATE,
        self::FUNC_OLD=>self::FUNC_TYPE_DATE,

        self::FUNC_TRIM=>self::FUNC_TYPE_STRING,
        self::FUNC_SUBSTR=>self::FUNC_TYPE_STRING,
        self::FUNC_STRREV=>self::FUNC_TYPE_STRING,
        self::FUNC_STRLEN=>self::FUNC_TYPE_STRING,
        self::FUNC_INSTR=>self::FUNC_TYPE_STRING,

        self::FUNC_IF=>self::FUNC_TYPE_EXP
    ];

    /**
     * 判断函数是否存在，存在返回函数类型
     * DateTime: 2023/2/15 10:28:38
     * @param string $funName 函数名
     * @return string 函数类型
     * @throws NotExistsError
     */
    public static function isFunc(string $funName): string
    {
        (isset(self::FUNC_TYPE[$funName])) ?: throw new NotExistsError(
            "函数 {$funName} 不存在"
        );
        return self::FUNC_TYPE[$funName];
    }

    /**
     * 执行函数
     * DateTime: 2023/2/15 10:33:46
     * @param string $funcName 函数名称
     * @param array $arg 参数
     * @return mixed
     * @throws NotExistsError
     */
    public static function executeFunc(string $funcName, array $arg): mixed
    {
        self::isFunc($funcName);
        return Execute::main($funcName, $arg);
    }


    #字段数据类型
    const FIELD_TYPE_STRING = 'string';
    const FIELD_TYPE_DATE = 'date';#Y-m-d
    const FIELD_TYPE_NUMBER = 'number';#包含整数小数
    const FIELD_TYPE = [
        self::FIELD_TYPE_STRING,
        self::FIELD_TYPE_DATE,
        self::FIELD_TYPE_NUMBER
    ];


    #字段所属类型:默认(关联左),关联右,新增字段
    const FIELD_BELONG_DEFAULT = 'field_belong_default';
    const FIELD_BELONG_JOIN = 'field_belong_join';
    const FIELD_BELONG_ADD = 'field_belong_add';
    const FIELD_BELONG = [
        self::FIELD_BELONG_DEFAULT,
        self::FIELD_BELONG_JOIN,
        self::FIELD_BELONG_ADD
    ];


    #数据源类型
    const INPUT_DATA_TYPE_SOURCE = 'source';
    #智能填报
    const INPUT_DATA_TYPE_FILL = 'fill';
    #文件excel
    const INPUT_DATA_TYPE_FILE = 'file';
    const INPUT_DATA_TYPE = [
        self::INPUT_DATA_TYPE_SOURCE,
        self::INPUT_DATA_TYPE_FILL,
        self::INPUT_DATA_TYPE_FILE
    ];


    #输出类型
    const OUTPUT_TYPE_FILE = 'file';
    const OUTPUT_TYPE = [
        self::OUTPUT_TYPE_FILE
    ];

    #关联类型
    #左外连接
    const JOIN_TYPE_LEFT = 'left';
    #右外连接
    const JOIN_TYPE_RIGHT = 'right';
    #内连接
    const JOIN_TYPE_INNER = 'inner';
    const JOIN_TYPE = [
        self::JOIN_TYPE_LEFT,
        self::JOIN_TYPE_RIGHT,
        self::JOIN_TYPE_INNER
    ];


    #合并列
    #是否删除合并列 1是否
    const DEL_FIELD_YES = 1;
    const DEL_FIELD_NO = 2;
    const DEL_FIELD = [
        self::DEL_FIELD_YES,
        self::DEL_FIELD_NO
    ];

    #输出文件类型
    const FILE_EXPORT_FORMAT_CVS = 'cvs';
    const FILE_EXPORT_FORMAT_XLS = 'xls';
    const FILE_EXPORT_FORMAT_XLSX = 'xlsx';
    const FILE_EXPORT_FORMAT_JSON = 'json';
    const FILE_EXPORT_FORMAT = [
        self::FILE_EXPORT_FORMAT_CVS,
        self::FILE_EXPORT_FORMAT_XLS,
        self::FILE_EXPORT_FORMAT_XLSX,
        self::FILE_EXPORT_FORMAT_JSON
    ];


    ##########################步骤类型#############################

    const STEP_TYPE = [
        self::STEP_TYPE_SOURCE => '数据源',
        self::STEP_TYPE_COLUMN_MERGE => '字段合并',
        self::STEP_TYPE_COMPUTE_COLUMN => '添加计算列',
        self::STEP_TYPE_FIELD_REPLACE => '字段值替换',
        self::STEP_TYPE_FILTER => '过滤',
        self::STEP_TYPE_GROUP => '分组',
        self::STEP_TYPE_JOIN => '关联',
        self::STEP_TYPE_OUTPUT => '输出',
        self::STEP_TYPE_REPEAT => '去重',
        self::STEP_TYPE_SEL_COLUMN => '选择列',
        self::STEP_TYPE_THEME => '导入主题库',
        self::STEP_TYPE_EMPTY_REPLACE => '空值替换'
    ];


    #[EtlStepType(
        desc: "输入数据集：数据源/文件(xls、xlsx、cvs)/智能填报",
        rule: [
            'input_data_type' => self::INPUT_DATA_TYPE_SOURCE,
            'obj_val' => 123,#对应数据源id、文件src、智能填报id
            'sql' => 'sql语句',
            'fields' => [#key对应：数据库=>字段名 智能填报=>问题id  excell=>A1、B1
                'id' => [
                    'desc' => 'id',#别名
                    'field_type' => self::FIELD_TYPE_NUMBER,#数据类型
                ],
                'name' => [
                    'desc' => 'name',
                    'field_type' => self::FIELD_TYPE_STRING
                ]
            ]
        ]
    )]
    const STEP_TYPE_SOURCE = 'source';


    #[EtlStepType(
        desc: '空值替换',
        rule: [
            self::FIELD_BELONG_DEFAULT => [
                'field' => '123',
                'field1' => '123'
            ],
            self::FIELD_BELONG_JOIN => [
                'field' => '12312',
                'field1' => '123123'
            ],
        ]
    )]
    const STEP_TYPE_EMPTY_REPLACE = 'empty_replace';


    #[EtlStepType(
        desc: "输出数据集",
        rule: [
            'output_type' => 'file',
            'format' => 'cvs',
        ]
    )]
    const STEP_TYPE_OUTPUT = 'output';


    #[EtlStepType(
        desc: '导入到主题库',
        rule: [
            'library_id' => '导入到主题库id'
        ]
    )]
    const STEP_TYPE_THEME = 'theme';


    #[EtlStepType(
        desc: '关联',
        rule: [
            'join_type' => self::JOIN_TYPE_INNER,
            'left_node_id' => 100,#左边关联输入数据源node id
            'right_node_id' => 103,#右边关联输入数据源node id
            self::FIELD_BELONG_DEFAULT => 'phone',#左表关联字段
            self::FIELD_BELONG_JOIN => 'mobile',#右表关联字段
            'show_field' => [#展示字段
                self::FIELD_BELONG_DEFAULT => [
                    'field1' => '别名1', 'field2' => '别名2'
                ],
                self::FIELD_BELONG_JOIN => [
                    'field1' => '别名1', 'field2' => '别名2'
                ]
            ],

        ]
    )]
    const STEP_TYPE_JOIN = 'join';


    #[EtlStepType(
        desc: '
                去重:数据库用字段、excel用表头A1、A2、智能填报用问题id
                如果有关联数据的情况左右表字段放对应字段、如果没有关联则放
                ',
        rule: [
            self::FIELD_BELONG_DEFAULT => ['sex', 'age'],#去重字段repeat_left_fields
            self::FIELD_BELONG_JOIN => ['sex', 'age'],#去重字段
        ]
    )]
    const STEP_TYPE_REPEAT = 'repeat';


    #[EtlStepType(
        desc: '选择列，如果没有关联则都放在sel_left_column字段',
        rule: [
            self::FIELD_BELONG_DEFAULT => [
                'sex' => '别名',
                'age' => '别名'
            ],#去重字段repeat_left_fields
            self::FIELD_BELONG_JOIN => [
                'sex' => '别名',
                'age' => '别名'
            ],
        ]
    )]
    const STEP_TYPE_SEL_COLUMN = 'sel_column';

    #[EtlStepType(
        desc: 'sql语句执行,只有数据库类型支持,且不支持关联',
        rule: [
            'sql' => 'select * from table'
        ]
    )]
    const STEP_TYPE_SQL = 'sql';


    #值类型 变量 函数 手动输入  字段
    const VAL_TYPE_VARIABLE = 'variable';
    const VAL_TYPE_FUNC = 'func';
    const VAL_TYPE_INPUT = 'input';
    const VAL_TYPE_FIELD = 'field';
    const VAL_TYPE = [
        self::VAL_TYPE_VARIABLE,
        self::VAL_TYPE_FUNC,
        self::VAL_TYPE_INPUT,
        self::VAL_TYPE_FIELD
    ];
    #计算列计算内容类型
    const COMPUTE_COLUMN_TYPE_FORMULA = 'formula';#计算公式
    const COMPUTE_COLUMN_TYPE_IF = 'if';#if表达式



    #[EtlStepType(
        desc: '计算添加列',
        rule:[
            [
                'field_name'=>'列名',
                'field_type'=>self::FIELD_TYPE_STRING,
                'field_key'=>'age',#具体计算列
                'rule'=>[
                    'formula'=>'2*12+b+c+d',#公式:公式必须包含变量
                    'variable'=>[ #变量 变量一定是函数
                        'a'=>[
                            'func'=>'max',
                            'arg'=>[
                                [
                                    'type'=>self::VAL_TYPE_VARIABLE,#参数类型：字段值，变量或者是自己输入的字符公式等等,
                                    'val'=>'b'
                                ],
                                [
                                    'type'=>'input',
                                    'val'=>5
                                ],
                                [
                                    'type'=>'field',#字段
                                    'val'=>[
                                        'belong'=>self::FIELD_BELONG_DEFAULT,#字段所属
                                        'field'=>'ats',#字段key
                                    ]
                                ]
                            ]
                        ],
                        'd'=>[
                            'func'=>'ceil',
                            'arg'=>[
                                [
                                    'type'=>'variable',
                                    'val'=>'b',#表示b变量的结果
                                ]
                            ]
                        ],
                        'e'=>[
                            'func'=>'if',#
                            'arg'=>[
                                [   #if表达式
                                    'compare'=>'>',#比较符 可以为空
                                    'before'=>[ #比较符前
                                        'type'=>'input',#
                                        'val'=>'2',#表示b变量的结果
                                    ],
                                    'after'=>[ #比较符后
                                        'type'=>'variable',
                                        'val'=>'b',#表示b变量的结果
                                    ]
                                ],
                                [   #是的结果 结果类型：variable input field 计算公式
                                    'type'=>'field',
                                    'val'=>'name',#表示b变量的结果
                                ],
                                [   #否的结果
                                    'type'=>'variable',
                                    'val'=>'c',#表示b变量的结果
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        ]
    )]
    const STEP_TYPE_COMPUTE_COLUMN ='compute_column';


    #[EtlStepType(
        desc: '字段值替换,只能选择原始列,不能选择新增列',
        rule: [
            self::FIELD_BELONG_DEFAULT => [
                'field' => [
                    '我的' => '我们的'
                ],
                'field1' => [
                    '我的' => '我们的'
                ]
            ],
            self::FIELD_BELONG_JOIN => [
                'field' => [
                    '我的' => '我们的'
                ],
                'field1' => [
                    '我的' => '我们的'
                ]
            ],
        ]
    )]
    const STEP_TYPE_FIELD_REPLACE = 'field_replace';


    #[EtlStepType(
        desc: '合并列',
        rule: [
            [
                'del_field' => 0,#是否删除合并前的列
                'split_symbol' => '-',#分割符号
                self::FIELD_BELONG_DEFAULT => [
                    'id', 'name', 'sex'
                ],
                self::FIELD_BELONG_JOIN => [
                    'id', 'name', 'sex'
                ],
                'column_name' => '合并列名'
            ]
        ]
    )]
    const STEP_TYPE_COLUMN_MERGE = 'column_merge';


    #[EtlStepType(
        desc: '分组',
        rule: [
            'group_filed' => '',#分组字段
            'belong' => self::FIELD_BELONG_DEFAULT,#分组字段所属表位置
            self::FIELD_BELONG_DEFAULT => [
                'field1' => 'avg',
                'field2' => 'sum'
            ],
            self::FIELD_BELONG_JOIN => [
                'age' => 'avg',#聚合字段
            ]
        ]
    )]
    const STEP_TYPE_GROUP = 'group';


    #[EtlStepType(
        desc: '筛选数据行',
        rule: [
            [
                'field' => 'age',
                'belong' => self::FIELD_BELONG_JOIN,
                'condition' => 'and',# and or
                'item' => [
                    [
                        'compare' => '>',#比较符
                        'value' => '10',#值 如果是范围[1,2]
                    ],
                    [
                        'compare' => "==",
                        'value' => '20',#值 如果是范围[1,2]
                    ],
                ]
            ],
            [
                'field' => 'age',
                'condition' => 'and',# and or
                'belong' => self::FIELD_BELONG_JOIN,
                'item' => [
                    [
                        'compare' => '>',#比较符
                        'value' => '20',#值 如果是范围[1,2]
                    ],
                    [
                        'compare' => '=',
                        'value' => '20',#值 如果是范围[1,2]
                    ],
                ]
            ],
        ]

    )]
    const STEP_TYPE_FILTER = 'filter';


    /**
     * 获取步骤常量注解
     * DateTime: 2023/2/9 10:49:19
     * @return array
     */
    public static function getStepTypeAttributes(): array
    {
        #ReflectionFunction
        $ref = new \ReflectionClass(self::class);

        $constant = $ref->getReflectionConstants();

        $arr = [];
        foreach ($constant as $itemMet) {
            $attributes = $itemMet->getAttributes(EtlStepType::class);
            if (!isset($attributes[0])) {
                continue;
            }
            $attr = $attributes[0]->newInstance();
            if (isset($attr->desc)) {
                $arr[] = [
                    'constants' => $itemMet->name,
                    'arg' => [
                        'desc' => $attr->desc,
                        'rule' => $attr->rule
                    ]
                ];
            }
        }

//        print_r($arr);
        return $arr;
    }


}




$arr  = GovEtlTaskModel::getStepTypeAttributes();


echo json_encode($arr,JSON_UNESCAPED_UNICODE);
