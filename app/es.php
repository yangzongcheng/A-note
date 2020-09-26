<?php
header("Content-Type:text/html;charset=utf-8");
require './vendor/autoload.php';
use Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();

/**
 * 搜索
 * @param $client
 * @return mixed
 */
function search($client){
    $params = [
        'index' => 'my_test_es',
        'body' => [
            'query' => [
                'match' => [
                    'content' => '*123*'
                ]
            ]
        ]
    ];
    $response = $client->search($params);
    return $response;
}

/**
 * 添加文档
 * @param $client
 */
function create($client){
    $params = [
        'index' => 'users',
        'id'    => 2,
        'body'  => [
            'name'     => 'zhangsn',
            'age'      => 10,
            'email'    => 'zs@gmail.com',
            'birthday' => '1990-12-12',
            'address'  => 'beijing'
        ]
    ];
    return $client->index($params);
}

function update($client){
    $params = [
        'index' => 'users',
        'id'    => 1,
        'body'  => [
            'doc' => [
                'name' => '李四'
            ]
        ]
    ];
    $response = $client->update($params);
    return $response;
}


/**
 * 获取单条
 * @param $client
 * @return mixed
 */
function get($client,$id){
    $params = [
        'index' => 'my_test_es',
        'id'    => $id
    ];
    $response = $client->get($params);
    return $response;
}

$set = search($client);
print_r($set);



