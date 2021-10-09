<?php
header("Content-Type:text/html;charset=utf-8");
require './vendor/autoload.php';
use Library\EsHandle;
$a =new EsHandle();

echo $a->test();