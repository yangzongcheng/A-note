<?php
require './vendor/autoload.php';
//use Datto\JsonRpc\Tests\Api;
use Datto\JsonRpc\Examples\Api;
use Datto\JsonRpc\Server;

$api = new Api();

$server = new Server($api);

$server->reply();