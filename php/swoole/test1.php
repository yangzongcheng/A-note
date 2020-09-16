<?php


use Swoole\Coroutine\Http\Client;

go(function () {
    $cli = new Swoole\Coroutine\Http\Client('127.0.0.1', 80);
    $cli->setHeaders([
        'Host' => "localhost",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $cli->set([ 'timeout' => 1]);
    $cli->get('http://127.0.0.1/index.php');
    echo $cli->body;
    $cli->close();
});