<?php

class Pdo
{


    /**
     * 连接pgsql
     * DateTime: 2022/10/21 17:20:33
     */
    private function pgSql()
    {
        #默认连public模式
        $dbconn = pg_connect("host=192.168.1.10 port=15432 dbname=mytest user=root password=root");
        $result = pg_query($dbconn, "select * from t_user");
        $data = pg_fetch_all($result);
        print_r($data);
        die;
    }


    /**
     * 连接pgsql
     * DateTime: 2022/10/21 17:20:22
     */
    private function sqlServer()
    {
        #默认连dbo模式
        $db = new  \PDO('sqlsrv:Server=192.168.1.10,1433;Database=zhixue',
            'SA','Abc12345');

        $sql = "select *  from t_user";

        $res = $db->query($sql)->fetchAll();

        foreach ($res as $item)
        {
            print_r($item);
        }
        die;
    }
}