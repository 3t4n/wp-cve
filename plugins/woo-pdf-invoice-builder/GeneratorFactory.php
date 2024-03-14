<?php


namespace rnwcinv;

require_once \RednaoWooCommercePDFInvoice::$DIR.'PDFGenerator.php';

class GeneratorFactory
{
    public static function GetGenerator($pageOptions,$order){
        if($pageOptions!=null&&isset($pageOptions->containerOptions)&&isset($pageOptions->containerOptions->splitPDF)&&$pageOptions->containerOptions->splitPDF&&\RednaoWooCommercePDFInvoice::IsPR())
            return new \rnwcinv\pr\MultiplePagesPDFGenerator\MultiplePagesPDFGenerator($pageOptions,false,$order);
        else
            return new \RednaoPDFGenerator($pageOptions,false,$order);
    }
}