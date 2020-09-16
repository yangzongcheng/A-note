<?php

include_once '../../func.php';
use Swoole\Coroutine\Channel;
use Swoole\Coroutine\MySQL;
use Swoole\Http\Server;


/***
 * Class MysqlPool
 * swoole 协程实现连接池
 */
class MysqlPool
{
    private $min; // 最小连接数
    private $max; // 最大连接数
    private $count; // 当前连接数
    private $connections; // 连接池
    protected $freeTime; // 用于空闲连接回收判断

    public static $instance;

    /**
     * MysqlPool constructor.
     */
    public function __construct()
    {
        $this->min = 10;
        $this->max = 100;
        $this->freeTime = 10 * 3600;
        $this->connections = new Channel($this->max + 1);
    }

    /**
     * @return MysqlPool
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 创建连接
     * @return MySQL
     */
    protected function createConnection()
    {
        $conn = new MySQL();
        $conn->connect([
            'host' => '127.0.0.1',
            'port' => '3306',
            'user' => 'root',
            'password' => '',
            'database' => 'test',
            'timeout'  => 5
        ]);

        return $conn;
    }

    /**
     * 创建连接对象
     * @return array|null
     */
    protected function createConnObject()
    {
        $conn = $this->createConnection();
        return $conn ? ['last_used_time' => time(), 'conn' => $conn] : null;
    }

    /**
     * 初始化连接
     * @return $this
     */
    public function init()
    {
        for ($i = 0; $i < $this->min; $i++) {
            $obj = $this->createConnObject();
            $this->count++;
            $this->connections->push($obj);
        }

        return $this;
    }

    /**
     * 获取连接
     * @param int $timeout
     * @return mixed
     */
    public function getConn($timeout = 3)
    {
        if ($this->connections->isEmpty()) {
            if ($this->count < $this->max) {
                $this->count++;
                $obj = $this->createConnObject();
            } else {
                $obj = $this->connections->pop($timeout);
            }
        } else {
            $obj = $this->connections->pop($timeout);
        }


        return $obj['conn']->connected ? $obj['conn'] : $this->getConn();
    }

    /**
     * 回收连接
     * @param $conn
     */
    public function recycle($conn)
    {
        if ($conn->connected) {
            $this->connections->push(['last_used_time' => time(), 'conn' => $conn]);
        }
    }

    /**
     * 回收空闲连接
     */
    public function recycleFreeConnection()
    {
        // 每 2 分钟检测一下空闲连接
        swoole_timer_tick(2 * 60 * 1000, function () {
            if ($this->connections->length() < intval($this->max * 0.5)) {
                // 请求连接数还比较多，暂时不回收空闲连接
                return;
            }

            while (true) {
                if ($this->connections->isEmpty()) {
                    break;
                }

                $connObj = $this->connections->pop(0.001);
                $nowTime = time();
                $lastUsedTime = $connObj['last_used_time'];

                // 当前连接数大于最小的连接数，并且回收掉空闲的连接
                if ($this->count > $this->min && ($nowTime - $lastUsedTime > $this->freeTime)) {
                    $connObj['conn']->close();
                    $this->count--;
                } else {
                    $this->connections->push($connObj);
                }
            }
        });
    }
}




$type = 2;
if($type==1){

    //swoole http 服务
    $http = new Swoole\Http\Server("0.0.0.0", 9501);

    $http->set(['work_num' => 1]);

    $http->on('WorkerStart', function ($request, $response) {
        MysqlPool::getInstance()->init()->recycleFreeConnection();
//    echoN('WorkerStart');
    });

    $http->on('request', function ($request, $response) {
        dd($request->get, $request->post);

        //获取连接
        $conn = MysqlPool::getInstance()->getConn();
        $query = $conn->query('SELECT * FROM t_user WHERE id>1');

        //回收连接
        MysqlPool::getInstance()->recycle($conn);

//dd($query);
        $response->header("Content-Type", "text/html; charset=utf-8");
        $response->end("<h1>Hello Swoole. #".rand(1000, 9999).json_encode($query)."</h1>");
    });

    $http->start();

}

if($type==2){
    //tcp server
    //创建Server对象，监听 127.0.0.1:9501端口
    $serv = new \Swoole\Server("127.0.0.1", 9501);

//监听连接进入事件
    $serv->on('Connect', function ($serv, $fd) {
        echo "Client: Connect.\n";

        MysqlPool::getInstance()->init()->recycleFreeConnection();
    });

//监听数据接收事件
    $serv->on('Receive', function ($serv, $fd, $from_id, $data) {

        //获取连接
        $conn = MysqlPool::getInstance()->getConn();
        $query = $conn->query('SELECT * FROM t_user WHERE id>1');

        //回收连接

        MysqlPool::getInstance()->recycle($conn);
        echo '接收：'.$data.'----'.date('Y-m-d H:i:s');
        $serv->send($fd, json_encode($query));


    });

//监听连接关闭事件
    $serv->on('Close', function ($serv, $fd) {
        echo "Client: Close.\n";
    });

//启动服务器
    $serv->start();


}




