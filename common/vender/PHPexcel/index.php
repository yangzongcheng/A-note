<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2018/4/18
 * Time: 11:16
 */
include_once "excel/PHPExcel.php";

include_once 'excel/excel_reader2.php';
class Excelinit{
    /**
     * @param $field_arr 顶部的标题
     * @param $data 数据二维数组
     * @param string $name 导出的文件名
     * 导出
     */
    function export($field_arr,$data,$name=''){
        $name = $name?$name:time();
        $objPHPExcel=new \PHPExcel();
        $objPHPExcel->getProperties()
            ->setTitle('Office 2007 XLSX Document')
            ->setSubject('Office 2007 XLSX Document')
            ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Result file');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

        $letter_arr = $this->letter_arr($field_arr);

        $obj = $objPHPExcel->setActiveSheetIndex(0);

        foreach ($letter_arr as $key=>$val){
            // $val[0]  标题
            $obj->setCellValue($val['letter'].'1',$val[0]);
        }

        $i=2;

        $letter = array_column($letter_arr,'letter');
        foreach($data as $k=>$v){
            $key=0;
            foreach ($v as $k1=>$v1){
                //v1 data值
                $obj->setCellValue($letter[$key].$i,$v1);
                $key++;
            }
            $i++;
        }

        $objPHPExcel->setActiveSheetIndex(0);

//生成xlsx文件
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename="'.$name.'.xlsx"');
//        header('Cache-Control: max-age=0');
//        $objWriter=\PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');

//*生成xls文件
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }


    /**
     * @param $file 文件路径
     * @param string $encoding 字符集编码
     * 修改防止高版本php报错
     */
    function import($file,$encoding='gbk'){
        $data = new \Spreadsheet_Excel_Reader1();
        $data->Spreadsheet_Excel_Reader($file);
        $data->setOutputEncoding($encoding);
        $data->read($file);
        return $data->sheets[0]["cells"];
    }


    /***
     * @param $lenth
     * @param int $start
     * @return array
     * 根据长度把数字转字母 26  A-Z     27=AA
     * 主要用于php excel操作
     */
    function letter_arr($field_arr,$start=65){
        $lenth = count($field_arr);
        for ($i=0;$i<$lenth;$i++){
            $str = '';
            if (floor($i / 26) > 0) {
                $str .= IntToChr(floor($i / 26)-1);
            }
            $letter =  $str . chr($i % 26 + $start);
            $field_arr[$i]['letter'] = $letter;
        }
        return $field_arr;
    }

    /**
     * 数字转字母 （类似于Excel列标）
     * @param Int $index 索引值
     * @param Int $start 字母起始值
     * @return String 返回字母
     * 数字转字母 0=A  25 = Z  26==AA
     */
    function IntToChr($index, $start = 65) {
        $str = '';
        if (floor($index / 26) > 0) {
            $str .= IntToChr(floor($index / 26)-1);
        }
        return $str . chr($index % 26 + $start);
    }
}




$obj = new Excelinit();

$field_arr = [['编号'],['姓名'],['性别']];
$data  = [['num'=>'1','name'=>'12313','sex'=>'男']];
//导出
//$obj->export($field_arr,$data,'123');

//导入
$A = $obj->import('./1.xls','gbk');
//foreach ($A as $key=>$val){
//    echo "['{$val[1]}'],".'<br>';
//}

