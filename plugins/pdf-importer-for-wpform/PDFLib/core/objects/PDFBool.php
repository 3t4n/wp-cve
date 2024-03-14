<?php


namespace rnpdfimporter\PDFLib\core\objects;


use Exception;
use rnpdfimporter\PDFLib\core\parser\PDFObjectParser;
use rnpdfimporter\PDFLib\core\syntax\CharCodes;

class PDFBool extends PDFObject
{
    public static $True;
    public static $False;

    public $value;

    public function __construct($enforcer,$value)
    {
        if($enforcer!=='{}')
        {
            throw new Exception('Private Constructor Error');
        }

        $this->value=$value;
    }

    public function asBoolean(){
        return $this->value;
    }

    public function _clone($context)
    {
        return $this;
    }

    public function __toString()
    {
        return $this->value?'true':'false';
    }

    public function sizeInBytes()
    {
        return $this->value?4:5;
    }

    public function copyBytesInto($buffer, $offset)
    {
        if ($this->value) {
            $buffer[$offset++] = CharCodes::t;
            $buffer[$offset++] = CharCodes::r;
            $buffer[$offset++] = CharCodes::u;
            $buffer[$offset++] = CharCodes::e;
            return 4;
        } else {
            $buffer[$offset++] = CharCodes::f;
            $buffer[$offset++] = CharCodes::a;
            $buffer[$offset++] = CharCodes::l;
            $buffer[$offset++] = CharCodes::s;
            $buffer[$offset++] = CharCodes::e;
            return 5;
        }
    }


}

PDFBool::$True=new PDFBool("{}",true);
PDFBool::$False=new PDFBool("{}",false);