<?php

    include '/opt/PHPExcel_1.7.8/Classes/PHPExcel.php'; 
    

    $objPHPExcel = PHPExcel_IOFactory::load('/home/cAdmin/group_discount_rules.xlsx');
    $objWorksheet = $objPHPExcel->getActiveSheet();
    
    $cellValue = $objWorksheet->getCell('A1')->getValue();

    print $cellValue;

    foreach ($objWorksheet->getRowIterator() as $row) 
    {
        print "\r\n";
        $cellIterator = $row->getCellIterator();

        foreach ($cellIterator as $cell) 
        {
            print $cell->getValue() . "\t";   

        }

    }
    
    $objPHPExcel->disconnectWorksheets();
    unset($objPHPExcel);


?>
