<?php

include_once 'excel/PHPExcel.php';
$name = time();
$data  = [['systemId'=>'1','ItemType'=>'12313']];
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

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1','系统编号')
    ->setCellValue('B1','设备项目分类');

$i=2;

foreach($data as $k=>$v){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,$v['systemId'])
        ->setCellValue('B'.$i,$v['ItemType']);
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