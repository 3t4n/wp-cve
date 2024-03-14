<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/23/2017
 * Time: 7:10 AM
 */

final class RednaoWooCommercePDFInvoice{
    public static $DIR;
}

spl_autoload_register('RedNaoWCInvLoader');
function RedNaoWCInvLoader($className)
{
    if(strpos($className,'rnwcinv\\')!==false)
    {
        $path=substr($className,7);
        $path=str_replace('\\','/', $path);
        include_once getcwd().$path.'.php';
    }
}

require 'vendor/autoload.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();



$dompdf->loadHtml('aa',array(),'UTF-8');
$dompdf->render();
/** @var DOMText $a */


// Output the generated PDF to Browser
$dompdf->stream("dompdf", array("Attachment" => 0));
var_dump($_dompdf_warnings);
die();


