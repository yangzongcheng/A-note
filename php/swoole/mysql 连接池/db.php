<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'on');
/**x
 *  PHP中的数据库 工具类设计
 *  郭璞
 *  2016年12月23日
 *
 **/
class DbHelper
{
    //连接池
    private $_pools = [];

    //连接池大小
    const POOLSIZE = 30;

    const USERNAME = "root";
    const PASSWORD = "";
    const HOST = "127.0.0.1";
    const DB = "test";

    public function __construct()
    {
        $db = self::DB;
        $username = self::USERNAME;
        $password = self::PASSWORD;
        $host = self::HOST;

        //持久化连接
        $presistent = array(PDO::ATTR_PERSISTENT => true);

        for ($i=0; $i < self::POOLSIZE; $i++) {
            $connection = new PDO("mysql:dbname=$db;host=$host", $username, $password);
            // sleep(3);
            array_push($this->_pools, $connection);
        }
    }

    //从数据库连接池中获取一个数据库链接资源
    public function getConnection()
    {
        echo '获取数据库资源，剩余资源:' . count($this->_pools) . "<br>";
        if (count($this->_pools) > 0) {
            $one = array_pop($this->_pools);
            echo '剩余资源:' . count($this->_pools) . "<br>";
            return $one;
        } else {
            throw new ErrorException ( "<mark>数据库连接池中已无链接资源，请稍后重试!</mark>" );
        }
    }

    //将用完的数据库链接资源放回到数据库连接池
    public function release($conn)
    {
        echo '资源回收 ：' . count($this->_pools) . "<br>";
        if (count($this->_pools) >= self::POOLSIZE) {
            throw new ErrorException ( "<mark>数据库连接池已满!</mark>" );
        } else {
            array_push($this->_pools, $conn);
            // $conn = null;
            echo '回收成功，剩余资源：' . count($this->_pools) . "<br>";
        }
    }

    public function query($sql)
    {
        try {
            $conn = $this->getConnection();
            $res = $conn->query($sql);
//            $this->release($conn);
            return $res;
        } catch (ErrorException $e) {
            print 'error:' . $e->getMessage();
            die;
        }
    }

    public function queryAll($sql)
    {
        try {
            $conn = $this->getConnection();
            $sth = $conn->prepare($sql);
            $sth->execute();
            $result = $sth->fetchAll();
            return $result;
        } catch (PDOException $e) {
            print 'error:' . $e->getMessage();
            die;
        }
    }
}


$sql = 'select * from t_user limit 1';

$dbhelper = new DbHelper;
for ($i=0; $i < 10; $i++) {
    $res = $dbhelper->query($sql);
     var_dump($res->fetchAll()) . PHP_EOL;
}