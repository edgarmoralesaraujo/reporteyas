<?php

class Xml{
    private $filename;

    function __construct($filename){
        $this->filename = $filename;
    }

    function read($tablaXMes){
        libxml_use_internal_errors(true); 
        $xml = simplexml_load_file($this->filename);
        $ns = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('c', $ns['cfdi']);
        $xml->registerXPathNamespace('t', $ns['tfd']);
        $comprobante = $xml->xpath('//cfdi:Comprobante')[0];
        $emisor = $xml->xpath('//cfdi:Comprobante//cfdi:Emisor')[0];
        $receptor = $xml->xpath('//cfdi:Comprobante//cfdi:Receptor')[0];
        $Concepto = $xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto')[0];
        $impuestos = $xml->xpath('//cfdi:Comprobante//cfdi:Impuestos')[1];
        $tfd = $xml->xpath('//t:TimbreFiscalDigital')[0];
    
        $anio_mes = substr((string) $comprobante['Fecha'], 0, 7);
    
        $tablaXMes[$anio_mes][(string) $tfd['UUID']] = [
            'emisorRfc'=>(string) $emisor['Rfc'],
            'emisorNombre'=>(string) $emisor['Nombre'],
            'receptorRfc'=>(string) $receptor['Rfc'],
            'receptorNombre'=>(string) $receptor['Nombre'],
            'serie'=>(string) $comprobante['Serie'],
            'folio'=>(string) $comprobante['Folio'],
            'tipoDeComprobante'=>(string) $comprobante['TipoDeComprobante'],
            'fecha'=>(string) $comprobante['Fecha'],
            'subtotal'=>(string) $comprobante['SubTotal'],
            'totalImpuestosTrasladados'=>(string) $impuestos['TotalImpuestosTrasladados'],
            'total'=>(string) $comprobante['Total'],
            'uuid'=>(string) $tfd['UUID'],
            'metodoDePago'=>(string) $comprobante['MetodoPago'],
            'formaDePago'=>(string) $comprobante['FormaPago'],
            'concepto'=>'Cantidad: '.(string)$Concepto['Cantidad'].' ValorUnitario: '.(string)$Concepto['ValorUnitario'].' Importe: '.(string)$Concepto['Importe'].' Descripcion: '.(string)$Concepto['Descripcion']  
        ];
        return $tablaXMes;
    }
}