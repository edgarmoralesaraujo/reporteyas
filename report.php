<?php
error_reporting(0);
require 'FabricaReporte.php';
require 'Xml.php';

$dataEmitidas = [];
$path = 'cfdis/emitidas/';
$dir = opendir($path);
while ($elemento = readdir($dir)){
    if( $elemento != "." && $elemento != ".."){
        if(!is_dir($path.$elemento)){
            $xml = new Xml($path.$elemento);
            $dataEmitidas = $xml->read($dataEmitidas);
        }
    }
}
closedir($dir);

$dataGastos = [];
$path = 'cfdis/gastos/';
$dir = opendir($path);
while ($elemento = readdir($dir)){
    if( $elemento != "." && $elemento != ".."){
        if(!is_dir($path.$elemento)){
            $xml = new Xml($path.$elemento);
            $dataGastos = $xml->read($dataGastos);
        }
    }
}
closedir($dir);

$reporte = new FabricaReporte();
$reporte->generar($dataEmitidas, $dataGastos);