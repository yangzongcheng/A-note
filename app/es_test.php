<?php
header("Content-Type:text/html;charset=utf-8");
require './vendor/autoload.php';
use Library\ElasticsearchService;

function cteateIndex()
{
    $indexName = 'test';
    $exist = ElasticsearchService::getInstance()->exist($indexName);
    if($exist){
        return false;
    }

    $createIndex = ElasticsearchService::getInstance()->createIndex(
        $indexName,
        [],
        [
            'field' => [
                'uid' => [],
                'name' => ['type' => 'text'],
                'tag' => [],
            ]
        ]
    );
    return $createIndex;
}


function add()
{
    $data['uid']=123434+mt_rand(1,9999999);
    $data['tag']=[32434,mt_rand(1,99999)];
    $data['name']='张三'.mt_rand(1,100000);
    $res = ElasticsearchService::getInstance()->add('test',$data,'');
    return $res;
}

while (true){
    add();
}



