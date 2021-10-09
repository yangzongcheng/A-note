<?php

phpinfo();die;
/**
 * @param $data
 * 打印
 */
function dd($data)
{
    echo "<pre>" . print_r($data, true);
}

function echoN($str)
{
    echo "\n";
    echo date('Y-m-d H:i:s'), ':' . $str;
    echo "<br>\n";
}