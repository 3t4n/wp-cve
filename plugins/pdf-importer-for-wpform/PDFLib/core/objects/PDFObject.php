<?php


namespace rnpdfimporter\PDFLib\core\objects;


use Exception;
use rnpdfimporter\PDFLib\core\PDFContext;

class PDFObject
{

    public function _clone($context)
    {
        throw new Exception('Object not implemented');
    }

    public function __toString()
    {
        return '';
    }

    public function sizeInBytes(){
        throw new Exception('Method not implemented');
    }

    public function copyBytesInto($buffer,$offset)
    {
        throw new Exception('Method not implemented');
    }


}