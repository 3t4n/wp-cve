<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 12/3/2018
 * Time: 8:09 AM
 */

namespace rnwcinv\utilities;


use RednaoPDFGenerator;
use RednaoWooCommercePDFInvoice;

class RNIoC
{
    public function getPDFGenerator($options,$useTestData=false,$order=null)
    {
        require_once RednaoWooCommercePDFInvoice::$DIR. 'PDFGenerator.php';
        return new RednaoPDFGenerator($options,$useTestData,$order);
    }


}