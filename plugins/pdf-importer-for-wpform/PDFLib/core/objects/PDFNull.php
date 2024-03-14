<?php


namespace rnpdfimporter\PDFLib\core\objects;


use rnpdfimporter\PDFLib\core\syntax\CharCodes;

class PDFNull extends PDFObject
{
    /** @var PDFNull */
    public static $Instance;
    public function asNull(){
        return null;
    }

    public function _clone($context){
        return $this;
    }

    public function __toString()
    {
        return 'null';
    }

    public function sizeInBytes()
    {
        return 4;
    }

    public function copyBytesInto($burffer, $offset)
    {
        $buffer[$offset++] = CharCodes::n;
        $buffer[$offset++] = CharCodes::u;
        $buffer[$offset++] = CharCodes::l;
        $buffer[$offset++] = CharCodes::l;
        return 4;
    }


}

PDFNull::$Instance=new PDFNull();