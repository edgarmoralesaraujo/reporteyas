<?php

require_once 'Classes/PHPExcel.php';
require 'ReporteEmitidas.php';
require 'ReporteGastos.php';

class FabricaReporte{
    private $objPHPExcel;

    function __construct(){
        $this->objPHPExcel = new PHPExcel();
        $this->objPHPExcel->getProperties()->setCreator("Edgar Morales")
                                    ->setLastModifiedBy("Edgar Morales")
                                    ->setTitle("Reporte Facturas")
                                    ->setSubject("Reporte CFDI")
                                    ->setDescription("Reporte de facturacion.")
                                    ->setKeywords("office 2007 openxml php")
                                    ->setCategory("Test result file");

        $this->objPHPExcel->setActiveSheetIndex(0);
    }

    public function generar($dataEmitidas, $dataGastos){
        $this->emitidas($dataEmitidas);
        $this->gastos($dataGastos);
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->saveDoc("Reporte");
    }

    private function emitidas($data){
        $sheet = $this->objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Emitidas');
        $re = new ReporteEmitidas($data, $sheet);
        $sheet = $re->getWorkSheet();
    }

    private function gastos($data){
        $sheet = $this->objPHPExcel->createSheet(1);
        $sheet->setTitle('Gastos');
        $rg = new ReporteGastos($data, $sheet);
        $sheet = $rg->getWorkSheet();
    }

    private function saveDoc($file_name){
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        $objWriter->save($file_name.'.xlsx');
        echo "Archivo guardado: ".$file_name.'.xlsx';
    }

    private function cellCollor(){
        $workSheet->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                 'rgb' => $color
            )
        ));
    }

    private function resizeColumns($data){
        
    }
}