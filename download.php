<?php

require_once 'function.php';

$url = checkUrl($_POST['url']);
$table = checkRobots($url);
if (isset($table)){

require_once 'Classes/PHPExcel.php';//Сторонняя библиотека
            $Excel = new PHPExcel();//обьект класса phpexel
            $Excel->setActiveSheetIndex(0);// указываем индекс активного листа
            $MySheet = $Excel->getActiveSheet();//обьект!!! активного листа
            $MySheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);//settings
            $MySheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            $MySheet->getPageMargins()->setTop(1);//settings
            $MySheet->getPageMargins()->setRight(0.75);//settings
            $MySheet->getPageMargins()->setLeft(0.75);//settings
            $MySheet->getPageMargins()->setBottom(1);//settings
            $MySheet->setTitle('Robots report');//title
            //set column
            $MySheet->getColumnDimension('A')->setWidth(3);
            $MySheet->getColumnDimension('B')->setWidth(50);
            $MySheet->getColumnDimension('C')->setWidth(10);
            $MySheet->getColumnDimension('D')->setWidth(15);
            $MySheet->getColumnDimension('E')->setAutoSize(true);
            $MySheet->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $MySheet->getStyle('A')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $MySheet->getStyle('B')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $MySheet->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $MySheet->getStyle('C')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $MySheet->getStyle('E')->getAlignment()->setWrapText(true);
            $MySheet->getStyle('E')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            $MySheet->getStyle('D')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            $headerColor = ['rgb'=>'a2c4c9'];//Header color
            $MySheet->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->applyFromArray($headerColor);
            $MySheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->applyFromArray($headerColor);
            $MySheet->getStyle('C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->applyFromArray($headerColor);
            $MySheet->getStyle('D2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->applyFromArray($headerColor);
            $MySheet->getStyle('E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->applyFromArray($headerColor);
            
            //add header
            $MySheet->mergeCells('A1:E1');
            $MySheet->setCellValue('A1', $url);
            $MySheet->setCellValue('A2', '№');
            $MySheet->setCellValue('B2', 'Название проверки');
            $MySheet->setCellValue('C2', 'Статус');
            $MySheet->setCellValue('D2', '');
            $MySheet->setCellValue('E2', 'Текущее состояние');
            $MySheet->mergeCells('A3:E3');
            //loop
            $c = 4;
            for($i = 0; $i < count($table); $i++){
                    $MySheet->getRowDimension($c)->setRowHeight(15);
                    $MySheet->getRowDimension($c+1)->setRowHeight(40);
                    $MySheet->mergeCellsByColumnAndRow( 0, $c , 0 , ($c+1) );
                    $MySheet->mergeCellsByColumnAndRow(1 , $c , 1 , ($c+1) );
                    $MySheet->mergeCellsByColumnAndRow( 2 , $c , 2 , ($c+1) );
                    $MySheet->setCellValue('A'.$c, ($i+1));
                    $MySheet->setCellValue('B'.$c, $table[$i][0]);
                    $MySheet->setCellValue('C'.$c, $table[$i][1]);//ok
                        if ($table[$i][1] == 'Ok'){
                            $MySheet->getStyle('C'.$c)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->applyFromArray(array('rgb'=>'93c47d'));
                        }else{
                            $MySheet->getStyle('C'.$c)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->applyFromArray(array('rgb'=>'e06666'));
                        }
                    
                    $MySheet->setCellValue('D'.$c, 'Состояние');
                    $MySheet->setCellValue('D'.($c+1), 'Рекомендации');
                    $MySheet->setCellValue('E'.$c, $table[$i][2]);
                    $MySheet->setCellValue('E'.($c+1), $table[$i][3]);
                    $MySheet->mergeCells('A'.($c+2).':E'.($c+2));
                    $c+=3;
            }
            
            $objWriter = PHPExcel_IOFactory::createWriter($Excel, 'Excel5');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=simple.xlsx');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
}
?>