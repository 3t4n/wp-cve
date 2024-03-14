<?php


namespace rnpdfimporter\PDFLib\core\document;


use rnpdfimporter\PDFLib\core\syntax\CharCodes;
use rnpdfimporter\PDFLib\utils\strings;

class PDFTrailer
{
    public $lastXRefOffset;

    public static function forLastCrossRefSectionOffset($offset)
    {
        return new PDFTrailer($offset);
    }

    private function __construct($lastXRefOffset)
    {
        $this->lastXRefOffset = \strval($lastXRefOffset);
    }

    public function __toString()
    {
        return "startxref\n" . $this->lastXRefOffset . "\n%%EOF";
    }

    public function sizeInBytes()
    {
        return 16 + \strlen($this->lastXRefOffset);
    }

    public function copyBytesInto($buffer, $offset)
    {
        $initialOffset = $offset;

        $buffer[$offset++] = CharCodes::s;
        $buffer[$offset++] = CharCodes::t;
        $buffer[$offset++] = CharCodes::a;
        $buffer[$offset++] = CharCodes::r;
        $buffer[$offset++] = CharCodes::t;
        $buffer[$offset++] = CharCodes::x;
        $buffer[$offset++] = CharCodes::r;
        $buffer[$offset++] = CharCodes::e;
        $buffer[$offset++] = CharCodes::f;
        $buffer[$offset++] = CharCodes::Newline;

        $offset += strings::copyStringIntoBuffer($this->lastXRefOffset, $buffer, $offset);

        $buffer[$offset++] = CharCodes::Newline;
        $buffer[$offset++] = CharCodes::Percent;
        $buffer[$offset++] = CharCodes::Percent;
        $buffer[$offset++] = CharCodes::E;
        $buffer[$offset++] = CharCodes::O;
        $buffer[$offset++] = CharCodes::F;

        return $offset - $initialOffset;
    }
}