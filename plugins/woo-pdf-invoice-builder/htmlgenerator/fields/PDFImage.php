<?php

namespace  rnwcinv\htmlgenerator\fields;



/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFImage extends PDFFieldBase
{

    protected function InternalGetHTML()
    {
        $fieldId=$this->GetPropertyValue('URL_ID');
        if($fieldId==''||get_attached_file($fieldId)=='')
            $path=\RednaoWooCommercePDFInvoice::$DIR.'images/temporalImage.png';
        else
            $path=get_attached_file($fieldId);
        return '<img '.$this->CreateStyleString(array(
                'width'=>$this->GetStyleValue('width'),
                'height'=>$this->GetStyleValue('height')

            )).' src="'.htmlspecialchars($path).'"/>';

    }
}