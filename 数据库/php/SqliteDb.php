<?php

namespace Provider;


/**
 *
 * 案例查看 demo()方法
 * sqlite 查询构建器
 * DateTime:  2023/4/10 14:33:50
 * ClassName: SqliteDb
 * @package Provider
 */
class SqliteDb
{
    /***
     *
     *
     *  命令操作
     *  sqlite3 ./dbss.db   -- 打开数据库
     *  .help -- 帮助命令
     *  .tables -- 列出所有表
     *  .schema t_student -- 查看表结构
     *
     */


    private static \PDO $dbCon;

    #操作的表
    private string $table;

    private string $field = '*';

    private ?int $offset = 0;
    private ?int $limit = 0;
    private string $order = '';
    private string $group = '';
    private string $where = '';#手动写条件

    #数据类型
    const FIELD_TYPE =[
        'INTEGER',#自增只能使用此种数据类型
        'TEXT',
        'INT',
        'REAL',
        'VARCHAR',
        'CHAR',
    ];

    /**
     * 关键词
     * 查询字段关键词特殊处理
     * @var array
     */
    private array $keyWold =[
        'RANGE',
        'ABS',
        'MOD',
        'SUM',
        'AVG',
        'MAX',
        "MIN",
        "DESC"
    ];


    /**获取db
     * DateTime: 2023/4/10 10:16:29
     * @param string $filePath
     * @return \PDO
     */
    public static function db(string $filePath=''):\PDO
    {
        $con = new \PDO('sqlite:'.$filePath);
        return $con;
    }

    /**获取db 内存方式
     * DateTime: 2023/4/10 10:16:29
     * @param string $filePath
     * @return \PDO
     */
    public static function dbMemory():\PDO
    {
        $con = new \PDO('sqlite:memory');
        return $con;
    }

    /**
     * 使用入口方法
     * DateTime: 2023/4/10 10:19:07
     * @param \PDO $db
     * @return \sqllite\SqliteDb
     */
    public static function from(string $filePath):self
    {
        self::$dbCon = self::db($filePath);;
        return new self();
    }

    /**
     * 使用入口方法
     * DateTime: 2023/4/10 10:19:07
     * @param \PDO $db
     * @return SqliteDb
     */
    public static function fromMemory():self
    {
        self::$dbCon = self::dbMemory();

        return new self();
    }



    /**
     * 设置操作的表
     * DateTime: 2023/4/10 11:23:25
     * @param string $table
     * @return $this
     */
    public function table(string $table):self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * 删除表
     * DateTime: 2023/4/10 11:26:36
     * @return bool
     * @throws \Exception
     */
    public function dropTable():bool
    {
        $table = $this->table;
        $this->exec(
            "DROP TABLE $table"
        );
        return true;
    }

    /**
     * 执行exec
     * DateTime: 2023/4/10 11:16:26
     * @param string $sql
     * @return mixed
     * @throws \Exception
     */
    public function exec(string $sql):mixed
    {
        try {
            self::$dbCon->exec($sql);
        }catch (\Throwable $throwable)
        {
            $this->throw($throwable->getMessage());
        }
        return true;
    }

    /**
     * 创建表
     * DateTime: 2023/4/10 11:16:54
     * @param string $tableName
     * @param array $fieldArr
     * @return bool
     * @throws \Exception
     */
    public function createTable(string $tableName,array $fieldArr):bool
    {
        $sql ="CREATE TABLE IF  NOT EXISTS $tableName (";
        $i = 1;
        $len = count($fieldArr);
        foreach ($fieldArr as $item)
        {
            $fieldSql ='
            '.$item['field'].' '.$item['type']. " ".$item['format'];
            if($i!=$len)
            {
                $fieldSql.=',';
            }
            $sql.=$fieldSql;
            $i++;
        }
        $sql.='
        )';
        $this->exec($sql);
        return true;
    }

    /**
     * 插入数据支持多条
     * DateTime: 2023/4/10 13:27:09
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function insert(array $data):mixed
    {
        $funcField = function (array $fieldArr)
        {
            $sql ='INSERT INTO '.$this->table.'(';
            $len = count($fieldArr);
            $i = 1;
            foreach ($fieldArr as $field)
            {
                $sql.=$field;
                if($i!=$len)
                {
                    $sql.=',';
                }
                $i++;
            }
            $sql.=')VALUES 
            ';
            return $sql;
        };
        if(is_array(array_values($data)[0]))
        {
            #多条
            $field = array_keys(array_values($data)[0]);
        }else{
            #单条
            $field = array_keys($data);
            $data = [$data];
        }

        $lenAll = count($data);
        $len = count($data[0]);
        $j = 1;

        $sqlStr ='';
        foreach ($data as $item)
        {
            $i = 1;
            $sqlStr .='(';
            foreach ($item as $val)
            {
                $sqlStr.= "'$val'";
                if($i!=$len)
                {
                    $sqlStr.=',';
                }
                $i++;
            }
            $sqlStr.=')';
            if($lenAll!=$j)
            {
                $sqlStr.=',';
            }
            $j++;
        }
        $sql = $funcField($field);

        $query = $sql.$sqlStr.';';
        $exec = $this->exec($query);
        return $exec;
    }

    /**
     *ㅤ设置limit
     * User: yangzc
     * DateTime: 2021/1/16 下午3:15
     * @param int $limit
     * @return $this|object
     */
    public function limit(int $limit): object
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     *ㅤ设置offset
     * DateTime: 2021/1/16 下午3:15
     * @param int $offset
     * @return $this|object
     */
    public function offset(int $offset): object
    {
        $this->offset = $offset;
        return $this;
    }
    /**
     * 设置查询过滤字段
     * DateTime: 2023/4/10 13:55:29
     * @param string $field
     * @return $this
     */
    public function select(string $field='*'):self
    {
        $fields = func_get_args();
        if ($fields) {
            $str = '';
            foreach ($fields as $key => $val) {
//                $val = $val=="*"?$val:"`$val`";
                if(in_array(strtoupper($val),$this->keyWold))
                {
                    $val = "`{$val}`";
                }
//                $val = self::$db->real_escape_string($val);
                $str .= $key == 0 ? $val : ',' . $val;
            }
            $this->field = $str;
        }
        return $this;
    }


    /**
     * 设置条件
     * DateTime: 2023/4/10 14:21:28
     * @param string $where
     * @return $this
     */
    public function where(string $where):self
    {
        $this->where = $where;
        return $this;
    }

    /**
     *ㅤ分页
     * DateTime: 2021/1/16 下午3:22
     * @param $page 1为第一页
     * @param $limit
     * @return string
     */
    public function paginate(int $page, int $limit):self
    {
        $offset = ($page - 1) * $limit;//开始去数据的位置
        $this->offset = $offset;
        $this->limit = $limit;
        return $this;
    }

    /**
     *ㅤ设置排序,支持多字段排序
     * DateTime: 2021/1/16 下午4:01
     * @param string $field
     * @param string $orderType ASC DESC
     * @return $this|object
     */
    public function order(string $field, string $orderType = 'ASC'): object
    {
        if ($field) {
            if($this->order)
            {
                $this->order .=','. $field . ' ' . $orderType;
            }else{
                $this->order =  $field . ' ' . $orderType;
            }
        }
        return $this;
    }

    /**
     *ㅤ分组
     * DateTime: 2021/1/16 下午4:19
     * @param string $group
     * @return $this|object
     */
    public function group(string $group): object
    {
        if ($group) {
            $this->group = $group;
        }
        return $this;
    }

    /**
     * 执行查询语句
     * DateTime: 2023/4/10 14:07:13
     * @param string $sql
     * @return mixed
     * @throws \Exception
     */
    public function query(string $sql):mixed
    {
        try {
            $query = self::$dbCon->query($sql)?->fetchAll();
        }catch (\Throwable $throwable)
        {
            $this->throw($throwable->getMessage());
        }
        return $query;
    }

    /**
     * 统一抛出异常
     * DateTime: 2023/4/10 14:14:45
     * @param string $msg
     * @return bool
     * @throws \Exception
     */
    private function throw(string $msg):bool
    {
        throw new \Exception($msg);
        return true;
    }
    /**
     *ㅤ生成查询sql
     * DateTime: 2021/1/16 下午3:10
     * @return string
     */
    private function handleSql(): string
    {
        if(!$this->table){
            $this->throw('table not null');
        }
        $sql = "select " . $this->field . " from {$this->table}";
        if ($this->where) {
            $sql .= ' where ' . $this->where;
        }


        if ($this->group) {
            $sql .= ' GROUP BY ' . $this->group;
//            if ($this->having) {
//                $sql .= ' HAVING ' . $this->having;
//            }
        }

        if ($this->order) {
            $sql .= " order by {$this->order}";
        }

        if ($this->limit) {
            $sql .= " limit {$this->limit}";
            if (is_int($this->offset)) {
                $sql .= " offset " . $this->offset;
            }
        }

        return $sql;
    }

    /**
     * 获取全部数据
     * DateTime: 2023/4/10 14:06:07
     * @return mixed
     */
    public function get()
    {
//        print_r($this->handleSql());die;
        $query = $this->query(
            $this->handleSql()
        );
        return $query;
    }


    /**
     * 使用demo
     * DateTime: 2023/4/10 14:30:41
     * @throws \Exception
     */
    private function demo()
    {

        $dbFilePath ='./db/dbss.db';


#查询
        $rows = SqliteDb::from($dbFilePath)
            ->table('t_test')
            ->select('id','name')
            ->limit(2)
            ->paginate(2,2)
            ->order('id','desc')
            ->group('name')
            ->where('id=1')
            ->get();

        print_r($rows);
        die;

        #插入单条数据
        SqliteDb::from($dbFilePath)->table('t_student')->insert([
            'id'=>1,
            'name'=>'张三'.time(),
            'sex'=>1,
            'desc'=>date('Y-m-d H:i:s')
        ]);

        #插入多条数据
        SqliteDb::from($dbFilePath)->table('t_test')->insert(
            [
                [
//            'id'=>3,
                    'name'=>'张三'.mt_rand(1,100),
                    'sex'=>1,
                    'desc'=>date('Y-m-d H:i:s')
                ],
                [
//            'id'=>4,
                    'name'=>'张三'.mt_rand(1,100),
                    'sex'=>1,
                    'desc'=>date('Y-m-d H:i:s')
                ]
            ]
        );



        #表结构 autoincrement 自增
        $tableField =[
            [
                'field'=>'id',
                'type'=>'INTEGER',
                'format'=>' PRIMARY KEY autoincrement' #自增主键
            ],
            [
                'field'=>'name',
                'type'=>'varchar(256)',
                'format'=>'not null'
            ],
            [
                'field'=>'sex',
                'type'=>'tinyint',
                'format'=>'not null'
            ],
            [
                'field'=>'desc',
                'type'=>'char(50)',
                'format'=>'null'
            ],
        ];

        #创建表
        SqliteDb::from(filePath: $dbFilePath)->createTable('t_test',$tableField);
    }
}