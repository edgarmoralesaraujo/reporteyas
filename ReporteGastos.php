<?php
require_once 'Classes/PHPExcel.php';
require_once 'Reporte.php';

class ReporteGastos implements Reporte{
    private $workSheet;
    private $data = [];

    public function getWorkSheet(){
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
        $this->workSheet->setCellValue('A'.$fila, 'RFC Emisor')
                        ->setCellValue('B'.$fila, 'Nombre Emisor')
                        ->setCellValue('C'.$fila, 'Serie')
                        ->setCellValue('D'.$fila, 'Folio')
                        ->setCellValue('E'.$fila, 'Fecha')
                        ->setCellValue('F'.$fila, 'SubTotal')
                        ->setCellValue('G'.$fila, 'Total Impuesto Trasladado')
                        ->setCellValue('H'.$fila, 'Total')
                        ->setCellValue('I'.$fila, 'UUID')
                        ->setCellValue('J'.$fila, 'Metodo de Pago')
                        ->setCellValue('K'.$fila, 'Forma de Pago')                        
                        ->setCellValue('L'.$fila, 'Conceptos');

        $this->cellColor('36FF33', $fila);
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
                $this->workSheet->setCellValue('A'.$fila, $uuid['emisorRfc'])
                                ->setCellValue('B'.$fila, $uuid['emisorNombre'])
                                ->setCellValue('C'.$fila, $uuid['serie'])
                                ->setCellValue('D'.$fila, $uuid['folio'])
                                ->setCellValue('E'.$fila, $uuid['fecha'])
                                ->setCellValue('F'.$fila, $uuid['subtotal'])
                                ->setCellValue('G'.$fila, $uuid['totalImpuestosTrasladados'])
                                ->setCellValue('H'.$fila, $uuid['total'])
                                ->setCellValue('I'.$fila, $uuid['uuid'])
                                ->setCellValue('J'.$fila, $uuid['metodoDePago'])
                                ->setCellValue('K'.$fila, $uuid['formaDePago'])
                                ->setCellValue('L'.$fila, $uuid['concepto'])
                                ->setCellValue('A1', $uuid['receptorNombre'])
                                ->setCellValue('A2', $uuid['receptorRfc']);

                $suma_subtotal += (double)$uuid['subtotal'];
                $suma_traslados += (double)$uuid['totalImpuestosTrasladados'];
                $suma_total += (double)$uuid['total'];
            }

            $fila++;
            $this->bold(); 
            $this->workSheet->setCellValue('E'.$fila, 'TOTALES')
                            ->setCellValue('F'.$fila, $suma_subtotal)
                            ->setCellValue('G'.$fila, $suma_traslados)
                            ->setCellValue('H'.$fila, $suma_total);
            $this->normal();

            $fila += 3;
        }

        foreach(range('A','L') as $columnID) {
            $this->workSheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
    }

    private function normal(){
        foreach(range('A','L') as $columnID) {                            
            $this->workSheet->getStyle($columnID)->getFont()->setBold(false);
        }
    }

    private function bold(){
        foreach(range('A','L') as $columnID) {                            
            $this->workSheet->getStyle($columnID)->getFont()->setBold(true);
        }
    }

    public function cellColor($color, $fila){
        foreach(range('A','L') as $columnID) { 
            $this->workSheet->getStyle($columnID.$fila)->getFill()->applyFromArray(array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => $color
                )
            ));
        }
    }
}