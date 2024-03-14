<?php


namespace rnpdfimporter\PDFLib\core\document;


use rnpdfimporter\PDFLib\core\syntax\CharCodes;
use rnpdfimporter\PDFLib\utils\strings;

class PDFHeader
{
    public $major;
    public $minor;

    public static function forVersion($major,$minor)
    {
        return new PDFHeader($major,$minor);
    }

    public function __construct($major,$minor)
    {
        $this->major=$major;
        $this->minor=$minor;
    }

    public function __toString()
    {
        $bc=chr(129);
        return "%PDF-".$this->major.$this->minor."\n%".$bc.$bc.$bc.$bc;
    }

    public function  sizeInBytes() {
        return 12 + \strlen($this->major) + \strlen($this->minor);
    }

    public function copyBytesInto($buffer, $offset) {
        $initialOffset = $offset;

        $buffer[$offset++] = CharCodes::Percent;
        $buffer[$offset++] = CharCodes::P;
        $buffer[$offset++] = CharCodes::D;
        $buffer[$offset++] = CharCodes::F;
        $buffer[$offset++] = CharCodes::Dash;

        $offset += strings::copyStringIntoBuffer($this->major, $buffer, $offset);
        $buffer[$offset++] = CharCodes::Period;
        $offset += strings::copyStringIntoBuffer($this->minor, $buffer, $offset);
        $buffer[$offset++] = CharCodes::Newline;

        $buffer[$offset++] = CharCodes::Percent;
        $buffer[$offset++] = 129;
        $buffer[$offset++] = 129;
        $buffer[$offset++] = 129;
        $buffer[$offset++] = 129;

        return $offset - $initialOffset;
    }
}