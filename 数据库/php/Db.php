<?php


namespace Provider;

use Database\SimpleDB;
use Zxedu\Particle\Error\NotExistsError;
use Zxedu\Particle\Error\SystemError;

/**
 * DateTime: 2020/12/18
 * Class DB
 * @package Provider
 */
class Db
{
    private string $table='';
    private string $field = '*';
    private ?int $offset = 0;
    private ?int $limit = 0;
    private string $order = '';
    private string $group = '';
    private string $having = '';
    private $where = '';
    public static SimpleDB $db;
    public static string $sqlStatement='';
    /** @var bool 是否抛出异常 */
    private bool $throw = true;
    /** @var bool 是否查询总数 */
    private $selTotal =false;
    /** @var int 总数量 get之后才能调用 */
    public static int $rows=0;

    #排序
    const ORDER_DESC ='DESC';
    const ORDER_ASC ='ASC';


    /**
     * 关键词
     * 查询字段关键词处理
     * @var array
     */
    private static $keyWold =[
        'RANGE',
        'ABS',
        'MOD',
        'SUM',
        'AVG',
        'MAX',
        "MIN"
    ];

    /**
     * 私有化 防止外部实例化
     * DB constructor.
     */
    private function __construct()
    {
        if(!self::$db){
            $this->_throw('DB Error:db not error');
        }
    }

    /**
     * 获取db对象
     * DateTime: 2023/3/23 10:12:33
     * @param string $dbName
     * @return SimpleDB
     * @throws \Errors
     */
    public static function db(string $dbName):SimpleDB
    {
        #mysqli 链接对象
        return \IS::database()->get($dbName);
    }
    /**
     *ㅤ得到db对象
     * DateTime: 2021/1/16 下午3:12
     * @return Db
     */
    public static function from(object $db): Db
    {
        self::$db = $db;
        return new Db();
    }

    /**
     *ㅤ指定库
     * DateTime: 2021/1/16 下午3:12
     * @return Db
     */
    public static function formDb(string $dbName): Db
    {
        $db = self::db($dbName);
        self::$db = $db;
        return new Db();
    }

    /**
     * 获取db对象
     * DateTime: 2022/1/21 17:10:14
     * @return SimpleDB
     */
    public function getDb():SimpleDB
    {
        return self::$db;
    }


    /**
     *ㅤㅤ删除
     * User: yangzc
     * DateTime: 2021/1/18 上午10:05
     * @return int 受影响行
     * @throws \Errors
     */
    public function delete():int{
        $db = self::$db;
        if(!$this->table){
            $this->_throw("DB Error: table not null");
        }
        $table = $db->real_escape_string($this->table);
        if(!$this->where){
            $this->_throw("DB Error: where not null");
            return 0;
        }
        $where = $this->where;
        $sql = "DELETE FROM `$table` WHERE {$where}";
        self::$sqlStatement = $sql;
        $query = self::$db->query($sql);
        if (!$query) {
            $this->_throw("DB Error: {$db->error}({$db->errno})");
            return 0;
        }
        return self::$db->affected_rows;
    }


    /**
     * 将数字字段值减少
     * DateTime: 2022/3/7 14:40:36
     * @param string $field
     * @param int $number
     * @return int
     * @throws \Errors
     */
    public function setDec(string $field,int $number=1):int
    {
        if(!$this->table){
            $this->_throw("DB Error:table not null");
        }
        $sets = $field.'='.$field.'-'.$number;
        $table = self::$db->real_escape_string($this->table);
        $sql = "UPDATE `$table` SET $sets";
        if ($this->where) {
            $sql .= ' where ' . $this->where;
        }
        self::$sqlStatement = $sql;
        $query = self::$db->query($sql);
        if (!$query) {
            $db = self::$db;
            $this->_throw("DB Error: {$db->error}({$db->errno})");
        }
        return self::$db->affected_rows;
    }

    /**
     * 将数字字段值增加
     * DateTime: 2022/3/7 14:40:36
     * @param string $field
     * @param int $number
     * @return int
     * @throws \Errors
     */
    public function setInc(string $field,int $number=1):int
    {
        if(!$this->table){
            $this->_throw("DB Error:table not null");
        }
        $sets = $field.'='.$field.'+'.$number;
        $table = self::$db->real_escape_string($this->table);
        $sql = "UPDATE `$table` SET $sets";
        if ($this->where) {
            $sql .= ' where ' . $this->where;
        }
        self::$sqlStatement = $sql;
        $query = self::$db->query($sql);
        if (!$query) {
            $db = self::$db;
            $this->_throw("DB Error: {$db->error}({$db->errno})");
        }
        return self::$db->affected_rows;
    }

    /**
     *ㅤ更新
     * DateTime: 2021/1/18 上午9:43
     * @param $fields
     * @return int 受影响行
     * @throws \Errors
     */
    public function update(array $fields):int{
        if( !$fields )
        {
            // no fields to update
            return 0;
        }
        $sets = array();
        foreach ($fields as $key => $val) {
            $key = self::$db->real_escape_string($key);
            $val = self::$db->real_escape_string($val);
            $sets[] = "`$key`='$val'";
        }
        $sets = implode(',', $sets);
        if(!$this->table){
            $this->_throw("DB Error:table not null");
        }
        $table = self::$db->real_escape_string($this->table);
        $sql = "UPDATE `$table` SET $sets";
        if ($this->where) {
            $sql .= ' where ' . $this->where;
        }
        self::$sqlStatement = $sql;
        $query = self::$db->query($sql);
        if (!$query) {
            $db = self::$db;
            $this->_throw("DB Error: {$db->error}({$db->errno})");
        }
        return self::$db->affected_rows;
    }

    /**
     *ㅤ插入数据
     * DateTime: 2021/1/18 上午11:04
     * @param array $fields 多条用二维数组
     * @param bool $replace
     * @return int 插入一条返回主键 插入多条返回插入条数
     * @throws \Errors
     */
    public function insert(array $fields, bool $replace=false):int {
        $db = self::$db;
        $cmd = $replace ? 'REPLACE' : 'INSERT';
        $keys = $vals = array();
        if(!$this->table){
            $this->_throw("DB Error: table not null");
            return 0;
        }
        $table = $db->real_escape_string($this->table);
        $insertNumber = 0;
        if(is_array(array_values($fields)[0])){
            //插入多条
            $insertNumber = 1;
        }
        foreach ($fields as $key => $val) {
            if(is_array($val)){
                $value =[];
                if(!$keys){
                    $keys = array_keys($val);
                    foreach ($keys as $k0=>&$v0){
                        $keys[$k0] = '`'.$db->real_escape_string($v0).'`';
                    }
                }
                foreach ($val as $k=>$v){

                    if($v===null || $v===NULL)
                    {
                        $value[] = "NULL";
                    }else{
                        $v = $db->real_escape_string($v);
                        $value[] =is_numeric($v)?$v:"'{$v}'";
                    }
                }
                $fieldVal = implode(',',$value);
                $vals[] = "({$fieldVal})";
            }else{
                $field = $db->real_escape_string($key);
                $val = $db->real_escape_string($val);
                $keys[] = "`$field`";
                if($val===null || $val===NULL){
                    $vals[] = "NULL";
                }else{
                    $vals[] = "'$val'";
                }

            }
        }
        $keys = implode(',', $keys);
        //单条用VALUES  多条用VALUE
        if($insertNumber==0){
            $vals = implode(',', $vals);
            $sql = "$cmd INTO `$table`($keys) VALUES($vals)";

        }else{
            $vals = implode(',', $vals);
            $sql = "$cmd INTO `$table`($keys) VALUE$vals";
        }
        \IS::logger()->info("SimpleDB:insert:sql={$sql}");
        self::$sqlStatement = $sql;
        $query = $db->query($sql);
        if (!$query) {
            $this->_throw("DB Error: {$db->error}({$db->errno})");
            return 0;
        }
        if (!$db->affected_rows) {
            return 0;
        }
        if($insertNumber==0){
            return $db->insert_id;
        }else{
            return $db->affected_rows;
        }
    }

    /**
     * 获取字段默认值
     * DateTime: 2022/4/20 16:15:58
     * @param string $field
     * @return mixed
     * @throws \Errors
     */
    public function getFieldDefaultValue(string $field):mixed
    {
        $table = $this->table;
        $defaultSql = "select default(`{$field}`) as default_value from `{$table}` limit 1;";
        $fieldDefault = self::$db->getBySql($defaultSql);
        return $fieldDefault[0]['default_value'];
    }

    /**
     * 获取字段值及信息
     * DateTime: 2023/4/12 10:00:50
     * @return array
     * @throws \Errors
     */
    public function getFields():array
    {
        $table = $this->table;
        $sql ="SELECT 
                COL.TABLE_SCHEMA,
                COL.COLUMN_NAME, 
                COL.COLUMN_TYPE,
                COL.COLUMN_COMMENT ,
                COL.DATA_TYPE 
               FROM 
                   INFORMATION_SCHEMA.COLUMNS COL
               Where  
                   COL.TABLE_NAME='{$table}'";
        $rows = $this->query($sql);
        return $rows;
    }

    /**
     * 判断某个库存不存在
     * DateTime: 2022/6/27 15:31:58
     * @param string $dbName
     * @return bool 存在true
     * @throws \Errors
     */
    public function existDataBase(string $dbName):bool
    {
        $sql ="SELECT * FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '{$dbName}'";
        self::$sqlStatement = $sql;
        try {
            $query = self::$db->getBySql($sql);
            if($query)
            {
                return true;
            }
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return false;
    }


    /**
     * 判断表是否存在
     * DateTime: 2022/6/27 15:41:20
     * @param string $table
     * @return bool
     * @throws \Errors
     */
    public function existTable(string $table):bool
    {
        $sql ="SELECT COUNT(*) as num  FROM information_schema.TABLES WHERE table_name = '{$table}'";
        self::$sqlStatement = $sql;
        try {
            $query = self::$db->getBySql($sql);
            if($query && isset($query[0]['num']) && $query[0]['num'])
            {
                return true;
            }
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return false;
    }

    /**
     * 物理删除表
     * DateTime: 2023/4/11 11:37:46
     * @param string $table
     * @return bool
     */
    public function dropTable(string $table):bool
    {
        $this->execute(
            "drop table {$table}"
        );
        return true;
    }


    /**
     *ㅤ设置表名
     * DateTime: 2021/1/16 下午3:15
     * @param string $table
     * @return $this|object
     */
    public function table(string $table): object
    {
        $this->table = self::$db->real_escape_string($table);
        return $this;
    }


    /**
     * 执行非查询sql
     * DateTime: 2022/6/27 16:29:04
     * @param string $sql
     * @return int
     * @throws \Errors
     */
    public function execute(string $sql):int
    {
        $execute =0;
        self::$sqlStatement = $sql;
        try {
            $execute = self::$db->execute($sql);
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return $execute;
    }

    /**
     *ㅤ执行原生sql 只能执行select 语句
     * DateTime: 2021/1/18 下午2:09
     * @param string $sql
     * @return array
     * @throws \Errors
     */
    public function query(string $sql):array {
        $reArr =[];
        self::$sqlStatement = $sql;
        try {
            $reArr = self::$db->getBySql($sql);
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return $reArr;
    }

    /**
     * 以生成器的方式返回
     * DateTime: 2023/4/14 10:49:09
     * @param string $sql
     * @return \Generator
     * @throws \Errors
     */
    public function queryGenerator(string $sql):\Generator
    {
        self::$sqlStatement = $sql;
        try {
            $reArr = self::$db->generatorBySql($sql);
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return $reArr;
    }

    /**
     *ㅤ查询所有数据
     * DateTime: 2021/1/16 下午3:15
     * @return string
     */
    public function get(string $table = '', ?int $limit = null): array
    {
        $reArr =[];
        if ($table) {
            $this->table = self::$db->real_escape_string($table);
        }
        if ($limit) {
            $this->limit = $limit;
        }
        $sql =  $this->_toSelSql();

        self::$sqlStatement = $sql;
        try {
            $reArr = self::$db->getBySql($sql);
            if($this->selTotal){
                $this->_total();
            }
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }

        return $reArr;
    }


    /**
     * 结果以生成器的方式返回
     * DateTime: 2023/4/14 10:46:50
     * @param string $table
     * @param int|null $limit
     * @return array
     * @throws \Errors
     */
    public function getGenerator(string $table = '', ?int $limit = null):\Generator
    {
        if ($table) {
            $this->table = self::$db->real_escape_string($table);
        }
        if ($limit) {
            $this->limit = $limit;
        }
        $sql =  $this->_toSelSql();

        self::$sqlStatement = $sql;
        try {
            $reArr = self::$db->generatorBySql($sql);
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }

        return $reArr;
    }


    /**
     * 获取总行数
     * DateTime: 2022/1/6 11:11:51
     * @return int
     */
    public function getTotal():int
    {
        return self::$rows;
    }

    /**
     * 获取总行数
     * DateTime: 2022/1/6 11:11:51
     * @return int
     */
    public static  function total():int
    {
        return self::$rows;
    }


    /**
     * 对结果进行循环操作,添加字段或对已有字段做操作
     * DateTime: 2020/9/24 13:28:02
     * @param \Closure $callBack
     * @return array
     * @throws \Errors
     */
    public function foreach(\Closure $callBack):array
    {
        $reArr =[];
        $sql =  $this->_toSelSql();
        self::$sqlStatement = $sql;
        try {
            $reArr = self::$db->getBySql($sql);

            if($this->selTotal){
                $this->_total();
            }
            foreach ($reArr as $key=>&$val){
               $handle = $callBack($key,$val);
               if($handle){
                   $val = $handle;
               }
               #如果返回false 则清空当前这条数据
               if($handle===false)
               {
                   unset($reArr[$key]);
               }
            }
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return $reArr;
    }


    /**
     * 对结果进行闭包处理
     * DateTime: 2021/9/24 13:35:45
     * @param \Closure $callBack
     * @return mixed
     * @throws \Errors
     */
    public function chunk(\Closure $callBack)
    {
        $reArr =[];
        $sql =  $this->_toSelSql();
        self::$sqlStatement = $sql;
        try {
            $reArr = self::$db->getBySql($sql);

            if($this->selTotal){
                $this->_total();
            }

        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return $callBack($reArr);
    }


    /**
     *ㅤ查询一条数据
     * User: yangzc
     * DateTime: 2021/1/16 下午6:14
     * @return array
     */
    public function first():array {
        $reArr =[];
        $this->limit =1;
        $sql =  $this->_toSelSql();
        self::$sqlStatement = $sql;
        try {
            $query = self::$db->getBySql($sql);
            if($this->selTotal){
                $this->_total();
            }
            if(isset($query[0])){
                $reArr = $query[0];
            }
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return $reArr;
    }

    /**
     *ㅤ获取单个字段值
     * User: yangzc
     * DateTime: 2021/1/16 下午6:44
     * @param $field
     * @return array
     */
    public function value(string $field){
        $reVal='';
        $this->limit =1;
        $this->field = $field;
        $sql =  $this->_toSelSql();
        self::$sqlStatement = $sql;
        try {
            $query = self::$db->getBySql($sql);
            $exp = explode('.',$field);
            $field = $exp[1]??$field;

            if(isset($query[0]) && $query[0][$field]){
                $reVal = $query[0][$field];
            }
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return $reVal;
    }

    /**
     *ㅤ统计字段count
     * User: yangzc
     * DateTime: 2021/1/18 下午3:16
     * @param string $field
     * @return int
     * @throws \Errors
     */
    public function count(string $field):int{
        $reVal=0;
        $this->limit =1;
        $field = self::$db->real_escape_string($field);
        $this->field = "count({$field}) as num";
        $sql =  $this->_toSelSql();
        self::$sqlStatement = $sql;
        try {
            $query = self::$db->getBySql($sql);
            if(isset($query[0]) && $query[0]['num']){
                $reVal = $query[0]['num'];
            }
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return $reVal;
    }

    /**
     *ㅤ查询某个字段的平均数
     * DateTime: 2021/1/18 下午4:54
     * @param string $field
     * @return int
     * @throws \Errors
     */
    public function avg(string $field) {
        $reVal=0;
        $this->limit =1;
        $field = self::$db->real_escape_string($field);
        $this->field = "avg({$field}) as num";
        $sql =  $this->_toSelSql();
        self::$sqlStatement = $sql;
        try {
            $query = self::$db->getBySql($sql);
            if(isset($query[0]) && $query[0]['num']){
                $reVal = $query[0]['num'];
            }
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return $reVal;
    }


    /**
     *ㅤ查询某一列的值可以用
     * DateTime: 2021/1/18 下午1:35
     * @param string $field
     * @param string $keyField
     * @return array
     * @throws \Errors
     */
    public function column(string $field,string $keyField=''):array {
        $reArr =[];
        if($field){
            $this->field =$field;
        }

        if($keyField){
            //必须拼接在最后一个字段后面
            $this->field.=','.self::$db->real_escape_string($keyField);
        }
        try {
            $sql = $this->_toSelSql();
            self::$sqlStatement = $sql;
            $query = self::$db->getBySql($sql);
            if($query){
                if(!isset($query[0])){
                    return $reArr;
                }
                //判断field 是否存在keyField
               $selField =  explode(',',$field);
               $fieldNumber = count($selField);
               //真实字段
               $keyfi =  array_keys($query[0]);
               if($keyField){
                   if($fieldNumber==1){
//                       $keyField = array_pop($keyfi);
//                       $field =  array_pop($keyfi);
                       $reArr =  array_column($query,$field,$keyField);

                   }else{
//                       $keyHandleField  =array_pop($keyfi);
                       foreach ($query as $key=>$val){

//                           $k = $val[$keyHandleField];
//                           if(!in_array($keyField,$selField)){
//                                unset($val[$keyHandleField]);
//                           }
//                           $reArr[$k] = $val;

                           $reArr[$val[$keyField]] = $val;
                       }
                   }
               }else{
                    if($fieldNumber==1){
                        $reArr = array_column($query,array_pop($keyfi));
                    }else{
                        $reArr = $query;
                    }
               }
            }
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return $reArr;
    }

    /**
     *ㅤ是否抛出异常
     * User: yangzc
     * DateTime: 2021/1/18 上午11:20
     * @param string $message
     * @return $this|object
     */
    public function throw(bool $throw=true):object {
        $this->throw = $throw;
        return $this;
    }

    /**
     *ㅤ最新执行输出sql
     * DateTime: 2021/1/18 下午1:14
     * @return $this|object
     */
    public static function dumpSql(bool $output=true):string {
        if($output){
            echo self::$sqlStatement;
        }
        return self::$sqlStatement;
    }

    /**
     *ㅤ最新执行输出sql
     * DateTime: 2021/1/18 下午1:14
     * @return $this|object
     */
    public static function getLastSql(bool $output=true):string {
        if($output){
            Helper::dd(self::$sqlStatement);
        }
        return self::$sqlStatement;
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
     *ㅤ设置字段
     * DateTime: 2021/1/16 下午3:56
     * @param string $field
     * @param string $field1
     * @return $this|object
     */
    public function select(string $field = '', string $field1 = ''): object
    {
        $fields = func_get_args();
        if ($fields) {
            $str = '';
            foreach ($fields as $key => $val) {
//                $val = $val=="*"?$val:"`$val`";
                if(in_array(strtoupper($val),self::$keyWold))
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
     *ㅤ分组条件
     * DateTime: 2021/1/16 下午4:20
     * @param string $having
     * @return $this|object
     */
    public function having(string $having): object
    {
        if ($having) {
            $this->having = $having;
        }
        return $this;
    }


    /**
     * 单个数据条件构建成sql 条件
     * DateTime: 2022/7/28 14:22:18
     * @param array $where
     * @return string
     */
    public static function getWhereCondition(array $where):string
    {
//  demo      $where =['id'=>['in'=>[1,9,10,11,12,14]]];
        $childStr  ='';
        $whereType = ['>', '>=', '<', '<=','=','!=', '<>'];
        foreach($where as $key=> $val)
        {
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    if (in_array($k, $whereType)) {
                        $childStr .= $key . $k . "'{$v}'";
                    } else {
                        switch ($k) {
                            case 'like':
                                $childStr .= $key . ' like ' . "'{$v}'";
                                break;
                            case 'between':
                                $v[0] = is_numeric($v[0]) ? $v[0] : "'{$v[0]}'";
                                $v[1] = is_numeric($v[1]) ? $v[1] : "'{$v[1]}'";
                                $childStr .= $key . ' between ' . "{$v[0]} and {$v[1]}";
                                break;
                            case 'in':
                                foreach ($v as $k1 => &$v1) {
                                    $v1 = is_numeric($v1) ? $v1 : "'{$v1}'";
                                }
                                $in = implode(',', $v);
                                $childStr .= $key . ' in(' . $in . ')';
                                break;

                            case 'not_in':
                                foreach ($v as $k1 => &$v1) {
                                    $v1 = is_numeric($v1) ? $v1 : "'{$v1}'";
                                }
                                $in = implode(',', $v);
                                $childStr .= $key . ' not in(' . $in . ')';
                                break;
                            default:
                                break;
                        }
                    }
                }
            }else {
                //全匹配
                $val = is_numeric($val) ? $val : "'{$val}'";
                if ($childStr) {
                    $childStr .= $key . '=' . $val;
                } else {
                    $childStr .= $key . '=' . $val;
                }

            }
        }
        return $childStr;
    }


    /**
     *
     *ㅤwhere 条件
     * DateTime: 2021/1/16 下午4:38
     * @param array $where
     * @return $this
     */
    public function where(mixed $where):self
    {
        if ($where) {
            $str = '';
            if ($this->where) {
                $str .= $this->where;
            }
            if (is_array($where)) {
                $whereType = ['>', '>=', '<', '<=','=','!=', '<>'];
                $count = count($where);
                foreach ($where as $key => $val) {
                    $count--;
                    if (is_array($val)) {
                        foreach ($val as $k => $v) {
                            if (in_array($k, $whereType)) {
                                $str .= $this->_setWhere($key . $k . "'{$v}'", $str);
                            } else {
                                switch ($k) {
                                    case 'like':
                                        $str .= $this->_setWhere($key . ' like ' . "'{$v}'", $str);
                                        break;
                                    case 'between':
                                        $v[0] = is_numeric($v[0]) ? $v[0] : "'{$v[0]}'";
                                        $v[1] = is_numeric($v[1]) ? $v[1] : "'{$v[1]}'";
                                        $str .= $this->_setWhere($key . ' between ' . "{$v[0]} and {$v[1]}", $str);
                                        break;
                                    case 'in':
                                        foreach ($v as $k1 => &$v1) {
//                                            $v1 = is_numeric($v1) ? $v1 : "'{$v1}'";
                                            $v1 = '"'."{$v1}".'"';
                                        }
                                        $in = implode(',', $v);
                                        $str .= $this->_setWhere($key . ' in(' . $in . ')', $str);
                                        break;
                                    #不包含
                                    case 'not_in':
                                        foreach ($v as $k1 => &$v1) {
                                            $v1 = is_numeric($v1) ? $v1 : "'{$v1}'";
                                        }
                                        $in = implode(',', $v);
                                        $str .= $this->_setWhere($key . ' not in(' . $in . ')', $str);
                                        break;

                                    default:
                                        break;
                                }
                            }
                        }
                    } else {
                        //全匹配
                        $val = is_numeric($val) ? $val : "'{$val}'";
                        if ($str) {
                            $str .= $this->_setWhere($key . '=' . $val, $str);
                        } else {
                            $str .= $this->_setWhere($key . '=' . $val, $str);
                        }

                    }
                }
            } else if (is_string($where)) {
                //字符串条件
                $str .= $this->_setWhere($where, $str);
            }
            $this->where = $str;
        }
        return $this;
    }

    /**
     * 闭包条件，可以在条件里判断逻辑
     * @param \Closure $closure
     * @return object
     */
    public function whereClosure(\Closure $closure):self
    {
        $where =[];
        $closure($where);
        $this->where($where);
        return $this;
    }

    /**
     * demo:whereAutoBetween('event_at',[111,2222])
     * between 会根据结果自动判断，转换到小于小于判断
     * DateTime: 2023/3/15 16:06:41
     * @param string $field
     * @param array $value
     * @return $this
     */
    public function whereAutoBetween(string $field,array $value):self
    {
        $value = array_values($value);
        $where = [];
        if(isset($value[0],$value[0]) && $value[0]!=='' && $value[1]!='')
        {
            $where = [$field=>['between'=>$value]];
        }elseif(isset($value[0]) && $value[0]){
            $where = [
                $field=>[
                    '>='=>$value[0]
                ]
            ];
        }elseif (isset($value[1]) && $value[1]) {
            $where = [
                $field=>[
                    '<='=>$value[1]
                ]
            ];
        }
        $this->where($where);
        return $this;
    }

    /**
     *ㅤ调用此函数将查询总条数并赋值到静态变量
     * DateTime: 2021/1/20 下午4:37
     */
    public function setTotal():self {
        $this->selTotal = true;
        return $this;
    }

    /**
     *ㅤwhere or
     * DateTime: 2021/1/16 下午5:49
     * @param string $where
     * @return $this|object
     */
    public function whereOr(string $where): object
    {
        if ($this->where) {
            $this->where = "({$this->where})";
            $this->where = $this->where . ' OR ' . "({$where})";
        } else {
            $this->where = "({$where})";
        }

        return $this;
    }

    /**
     *ㅤ字符where
     * DateTime: 2021/2/3 下午4:23
     * @param string $where
     * @param string $relation 与前面的条件关联类型 and or
     * @return $this
     */
    public function whereToString(string $where,string $relation='AND'){
        if ($this->where) {
            $this->where = "({$this->where})";
            $this->where = $this->where . ' '.$relation.' ' . "({$where})";
        } else {
            $this->where = "({$where})";
        }

        return $this;
    }

    /**
     *
     *
     *
     *
     * demo
    $result = self::DbInstance()
        ->join('auth_user_data d inner join auth_user u on u.user_id=d.user_id')
        ->where([
        'd.group_id'=>$gid,
        'd.data_type'=>$dataType,
        'd.object_id'=>$objectId
        ])
        ->select('d.user_id','u.real_name')
        ->get();
     *ㅤ表连接
     * DateTime: 2021/1/16 下午6:03
     * @param string $join
     * demo: (student s inner join table t on t.id = s.tid )
     * demo:(inner join table t on t.id = s.tid) inner可以为 left right
     * demo:(student s,teacher t)
     * @return $this|object
     */
    public function join($join=''): object
    {
        if($this->table){
            $join = $this->table.' '.$join;
        }
        $this->table = $join;
        return $this;
    }

    /**
     *ㅤ生成查询sql
     * DateTime: 2021/1/16 下午3:10
     * @return string
     */
    private function _toSelSql(): string
    {
        if(!$this->table){
            $this->_throw('table not null');
        }
        $sql = "select SQL_CALC_FOUND_ROWS " . $this->field . " from {$this->table}";
        if ($this->where) {
            $sql .= ' where ' . $this->where;
        }


        if ($this->group) {
            $sql .= ' GROUP BY ' . $this->group;
            if ($this->having) {
                $sql .= ' HAVING ' . $this->having;
            }
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
     *ㅤ拼接sql
     * DateTime: 2021/1/16 下午5:22
     * @param $str
     * @param $count
     * @return string
     */
    private function _setWhere($str, $beforStr,string $cond='and'):string
    {
        return $beforStr ? " $cond " . $str : $str;
    }


    /**
     *ㅤ查询总条数
     * DateTime: 2021/1/20 下午4:29
     * @return mixed
     * @throws \Errors
     */
    private function _total():bool {
        $query =  self::$db->getBySql("SELECT FOUND_ROWS() as total;");
        if(isset($query[0]['total'])){
             self::$rows =  $query[0]['total'];
             return true;
        }
        $this->_throw('SELECT FOUND_ROWS Error');
        return false;
    }

    /**
     *ㅤ统一抛出异常
     * DateTime: 2021/1/18 上午11:24
     * @param string $msg
     * @param int $code
     * @return bool
     * @throws \Errors
     */
    private function _throw(string $msg, int $code=\Errors::ERRNO_SYSTEM):bool {
        if($this->throw){
            throw new \Errors('sql['.self::$sqlStatement.']'.$msg, $code);
        }
        return true;
    }









    #
    # 以下函数谨慎使用
    #


    /**
     * 不存在将抛出异常，存在则返回结果
     * DateTime: 2023/3/31 13:51:23
     * @param string $message
     * @return array
     * @throws \Errors
     */
    public function  nonExistThrow(string $message='操作对象不存在'):array
    {
        $sql =  $this->_toSelSql();
        self::$sqlStatement = $sql;
        try {
            $query = self::$db->getBySql($sql);
            if($this->selTotal){
                $this->_total();
            }
            $query?:throw new NotExistsError(
                $message
            );
            return $query;

        }catch (NotExistsError $exception){
            throw new NotExistsError(
                $exception->getMessage()
            );
        }
        return $this;
    }


    /**
     * 不存在将抛出异常，存在则返回结果
     * DateTime: 2023/3/31 13:51:23
     * @param string $message
     * @return array
     * @throws \Errors
     */
    public function  nonExistFirstThrow(string $message='操作对象不存在'):array
    {
        $sql =  $this->_toSelSql();
        self::$sqlStatement = $sql;
        try {
            $query = self::$db->getBySql($sql);
            if($this->selTotal){
                $this->_total();
            }
            $query?:throw new NotExistsError(
                $message
            );
            if(isset($query[0]))
            {
                return $query[0];
            }
            return $query;

        }catch (NotExistsError $exception){
            throw new NotExistsError(
                $exception->getMessage()
            );
        }
        return $this;
    }

    /**
     *
     *
     *
     * 用于处理结果
     * @var array
     */
    private array $existenceData=[];

    /**
     * 判断是否调用existence()
     * @var bool
     */
    private bool $callExistence = false;

    /**
     * 对结果进行赋值,用于闭包处理
     * DateTime: 2022/4/14 11:15:47
     * @param bool $one true 单条  false多条
     * @return $this
     * @throws \Errors
     */
    public function  existence(bool $one=true):self
    {
        $sql =  $this->_toSelSql();
        self::$sqlStatement = $sql;
        try {
            $query = self::$db->getBySql($sql);
            if($this->selTotal){
                $this->_total();
            }
            $this->callExistence = true;
            #只处理一条数据
            if($one===true &&  isset($query[0]))
            {
                $this->existenceData = $query[0];
            }
            if($one===false)
            {
                $this->existenceData = $query;
            }
        }catch (\Exception $exception){
            $this->_throw($exception->getMessage(),$exception->getCode());
        }
        return $this;
    }

    /**
     * 不存在时处理
     * DateTime: 2022/4/14 11:10:26
     * @param \Closure $closure
     * @return $this
     */
    public function existenceNon(\Closure $closure):self
    {
        if(!$this->callExistence){
            throw new SystemError(__CLASS__.'调用existence函数只能才能调用'.__FUNCTION__);
        }
        if(!$this->existenceData){
           $closure();
        }
        return $this;
    }

    /**
     * 存在处理
     * DateTime: 2022/4/14 11:11:48
     * @param array $data
     * @return array
     */
    public function existenceHandle(\Closure $closure):self
    {
        if(!$this->callExistence){
            throw new SystemError(__CLASS__.'调用existence函数只能才能调用'.__FUNCTION__);
        }
        if($this->existenceData)
        {
            $closure($this->existenceData);
        }
        return $this;
    }

    /**
     * 返回existenceData
     * DateTime: 2022/4/14 11:13:35
     * @return array
     */
    public function getExistenceData():array
    {
        if(!$this->callExistence){
            throw new SystemError(__CLASS__.'调用existence函数只能才能调用'.__FUNCTION__);
        }
        $reData = $this->existenceData;
        #清空
        $this->existenceData =[];
        return $reData;
    }


}



/*
//demo1
$sql1 = Db::from()->select('*', 'person_id')
    ->where([
        'id' => [
            '>' => 1,
            '<' => 2,
            'between'=>[1,2]
        ],
        'b' => 'a',
    ])->where('set = 1')->where([
        'c' => 123,
        'd' => ['like' => '%llll%']
    ])->where(['f' => 1])
    ->where('d=2')
    ->whereOr('g=2')
    ->get('attend');

//demo2
$sql2 = Db::from()->table('attend')->get();
//demo3
$sql3 = Db::from()->table('attend')->first();
//demo4
$sql4 = Db::from()->table('attend a')->join('inner join person p on p.person_id = a.person_id')->first();
//demo5
$sql5 = Db::from()->table('attend a')
    ->join('inner join person p on p.person_id = a.person_id')
    ->where(['a.person_id'=>62])
    ->get();
//demo6
$sql6 = Db::from()->table('attend a')
    ->join('left join person p on p.person_id = a.person_id')
    ->where(['a.person_id'=>642])
    ->get();
//demo7
$sql7 = Db::from()->table('attend a')
    ->join('right join person p on p.person_id = a.person_id')
    ->select('p.*')
    ->where(['a.person_id'=>642])
    ->get();
echo $sql1;




$sql = Db::from($this->_db)->table($this->_table)->select("count({$this->_pk}) as num")->where([
    'school_id'=>$schoolId,
    'attend_time'=>['between'=>[$todayTimeArr[0],$todayTimeArr[1]]]
])->get();



$update = Db::from($this->_db)->table($this->_table)->where([$this->_pk=>['<'=>3]])->delete(['person_name'=>'王大锤1','attend_time'=>time()]);

$insert = Db::from($this->_db)->table($this->_table)->insert([
    ['person_name'=>"李星云'",'attend_time'=>time()],
    ['person_name'=>"李茂贞",'attend_time'=>time()]
]);



$columnJoin = Db::from($this->_db)->join('person p inner join attend a on a.person_id=p.person_id')
    ->where(['a.'.$this->_pk=>['<='=>30]])->column('p.real_name,a.person_name','a.person_id');


$column = $update = Db::from($this->_db)->table($this->_table)
    ->where([$this->_pk=>['<'=>30]])->limit(2)->paginate(2,10)
    ->column('person_name,person_id,attend_id','attend_id');


//对结果进行循环操作 添加字段或对已有字段做操作
//如果 $val 没有使用指针则需要返回 return $val;
$result = Db::from($this->_db)->where([
    'provider_id'=>$this->providerId,
    'provider_campus_id'=>$this->sid
])->select('name','num','ratio')->foreach(function ($key,&$val){
    //对已有数据进行处理
    $val['ratio'] = $val['ratio']/100;
    //返回结果添加cc 字段
    $val['cc'] = time();
    //如果&$val 则不需要返回
    return $val;
});


//对结果进行闭包处理
$result = HexClassroomModel::TableInstance()->where([
    'provider_id'=>$this->providerId,
    'provider_campus_id'=>$this->sid
])->select('name','num','ratio')->chunk(function ($data){
//           print_r($data);
    foreach ($data as &$val){
        $val['item'] = 123;
    }
    return $data;
});


#demo

$form = FillFromModel::DbInstance()->join('fill_form f inner join fill_form_member m on f.id=m.form_id')
    ->where([
        'm.uid'=>$this->uid,
        'f.status'=>FillFromModel::STATUS_PUSH
    ])
    ->whereClosure(function (&$where){
        $time = time();
        #未填写的必须为未结束的表单
        if($this->type==FillFormMemberModel::FILL_STATUS_NO)
        {
            $where['m.fill_status'] = FillFormMemberModel::FILL_STATUS_NO;
            $where['f.fill_end_at'] = ['>'=>$time];
        }
        #已完成  已经到了结束时间未填写的表单自动进入已完成列表
        if($this->type==FillFormMemberModel::FILL_STATUS_YES)
        {
            $statusYes = FillFormMemberModel::FILL_STATUS_YES;
            $where = "(m.fill_status = {$statusYes} or f.fill_end_at<{$time})";
        }
    })
    ->whereClosure(function (&$where){

        $examineNo = FillFromModel::EXAMINE_NOT;
        $examineStatusPass = FillFromModel::EXAMINE_STATUS_PASS;
        $where = "(f.examine={$examineNo} or f.examine_status={$examineStatusPass})";
    })
    ->select(
        'f.id','f.title','f.fill_start_at','f.fill_end_at','f.uid','f.create_at',
        'f.repeat','f.repeat_week','f.fill_num'
    )
    ->order('m.create_at','desc')
    ->paginate($this->page,$this->limit)
    ->foreach(function ($k,&$item){
        $item['create_at'] = Timer::date($item['create_at']);
    });
*/
