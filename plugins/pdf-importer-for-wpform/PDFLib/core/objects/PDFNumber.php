<?php


namespace rnpdfimporter\PDFLib\core\objects;


use rnpdfimporter\PDFLib\utils\numbers;
use rnpdfimporter\PDFLib\utils\strings;

class PDFNumber extends PDFObject
{

    public function __construct($value)
    {
        $this->numberValue=$value;
        $this->stringValue=numbers::numberToString($value);
    }

    public static function of($value)
    {
        return new PDFNumber($value);
    }

    public $numberValue;
    public $stringValue;

    public function asNumber(){
        return $this->numberValue;
    }


    public function value(){
        return $this->numberValue;
    }

    public function _clone($context)
    {
        return PDFNumber::of($this->numberValue);
    }

    public function __toString()
    {
        return $this->stringValue;
    }

    public function sizeInBytes()
    {
        return \strlen($this->stringValue);
    }

    public function copyBytesInto($buffer, $offset)
    {
        $offset.=strings::copyStringIntoBuffer($this->stringValue,$buffer,$offset);
        return \strlen($this->stringValue);
    }


}
