<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/28
 * Time: 12:22
 */


//导入读取excel数据

include_once 'excel/excel_reader2.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('gbk');
$data->read('123.xls');
echo '<pre>';print_r($data->sheets[0]["cells"]);