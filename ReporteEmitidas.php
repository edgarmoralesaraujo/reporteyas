<?php
require_once 'Classes/PHPExcel.php';
require_once 'Reporte.php';

class ReporteEmitidas implements Reporte{
    private $workSheet;
    private $data = [];

    public function getWorkSheet(){
        $this->workSheet->setCellValue('D2', 'CANCELADA - NOMBRE INCORRECTO');
        $this->workSheet->getStyle('C2')->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => 'FCFF33'
            )
        ));
        $this->encabezado(5);
        $this->body();
        return $this->workSheet;
    }

    function __construct($data, $workSheet){
        $this->workSheet = $workSheet;
        $this->data = $data;
    }

    function encabezado($fila){
        $this->bold();
        $this->workSheet->setCellValue('A'.$fila, 'RFC Receptor')
                        ->setCellValue('B'.$fila, 'Nombre Receptor')
                        ->setCellValue('C'.$fila, 'Tipo')
                        ->setCellValue('D'.$fila, 'Fecha')
                        ->setCellValue('E'.$fila, 'SubTotal')
                        ->setCellValue('F'.$fila, 'Total Imp Trasladado')
                        ->setCellValue('G'.$fila, 'Total')
                        ->setCellValue('H'.$fila, 'UUID')
                        ->setCellValue('I'.$fila, 'Metod de Pago')
                        ->setCellValue('J'.$fila, 'Forma de Pago')
                        ->setCellValue('K'.$fila, 'Conceptos');
                        
        $this->cellColor('33DAFF', $fila);
        $this->normal();
    }

    function body(){
        $fila = 5;
        foreach($this->data as $mes){
            $suma_total = 0;
            $suma_traslados = 0;
            $suma_subtotal = 0;

            $this->encabezado($fila);
            foreach($mes as $uuid){
                $fila++;
                $this->workSheet->setCellValue('A'.$fila, $uuid['receptorRfc'])
                                ->setCellValue('B'.$fila, $uuid['receptorNombre'])
                                ->setCellValue('C'.$fila, $uuid['tipoDeComprobante'])
                                ->setCellValue('D'.$fila, $uuid['fecha'])
                                ->setCellValue('E'.$fila, $uuid['subtotal'])
                                ->setCellValue('F'.$fila, $uuid['totalImpuestosTrasladados'])
                                ->setCellValue('G'.$fila, $uuid['total'])
                                ->setCellValue('H'.$fila, $uuid['uuid'])
                                ->setCellValue('I'.$fila, $uuid['metodoDePago'])
                                ->setCellValue('J'.$fila, $uuid['formaDePago'])
                                ->setCellValue('K'.$fila, $uuid['concepto'])
                                ->setCellValue('A1', $uuid['emisorNombre'])
                                ->setCellValue('A2', $uuid['emisorRfc']);

                $suma_subtotal += (double)$uuid['subtotal'];
                $suma_traslados += (double)$uuid['totalImpuestosTrasladados'];
                $suma_total += (double)$uuid['total'];
            }

            $fila++;           
            $this->bold(); 
            $this->workSheet->setCellValue('D'.$fila, 'TOTALES')
                            ->setCellValue('E'.$fila, $suma_subtotal)
                            ->setCellValue('F'.$fila, $suma_traslados)
                            ->setCellValue('G'.$fila, $suma_total);
            $this->normal();

            $fila += 3;            
        }

        foreach(range('A','K') as $columnID) {
            $this->workSheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
    }

    private function normal(){
        foreach(range('A','K') as $columnID) {                            
            $this->workSheet->getStyle($columnID)->getFont()->setBold(false);
        }
    }

    private function bold(){
        foreach(range('A','K') as $columnID) {                            
            $this->workSheet->getStyle($columnID)->getFont()->setBold(true);
        }
    }

    public function cellColor($color, $fila){
        foreach(range('A','K') as $columnID) { 
            $this->workSheet->getStyle($columnID.$fila)->getFill()->applyFromArray(array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => $color
                )
            ));
        }
    }
}