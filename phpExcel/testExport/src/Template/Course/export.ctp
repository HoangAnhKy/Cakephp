<?php
$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->setCellValue('B' . 3, 'Created_at');
$objPHPExcel->getActiveSheet()->setCellValue('C' . 3, $date ?? '');

$i = 5;
if (!empty($data_export)) {
    foreach ($data_export as $k_data_export => $v_data_export) {
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, mb_strtoupper($k_data_export = $k_data_export + 1, 'UTF-8'));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, mb_strtoupper($v_data_export->id ?? '', 'UTF-8'));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, mb_strtoupper($v_data_export->name_course ?? '', 'UTF-8'));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, mb_strtoupper($v_data_export->created_at->format('d-m-Y') ?? '', 'UTF-8'));
        if ($k_data_export < count($data_export))
            $i++;
    }
}

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=' . $file ?? 'export');
header('Cache-Control: max-age=0');
header ('Pragma: public');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
