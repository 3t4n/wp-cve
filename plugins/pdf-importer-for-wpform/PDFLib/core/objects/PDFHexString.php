<?php


namespace rnpdfimporter\PDFLib\core\objects;


use DateTime;
use Exception;
use rnpdfimporter\js\src\lib\PDFLib\utils\pdfDocEncoding;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\utils\strings;
use rnpdfimporter\PDFLib\utils\unicode;

class PDFHexString extends PDFObject
{
    public $value;
    public static $Instance;
    public static function of($value)
    {
        return new PDFHexString($value);
    }

    public static function fromText($value)
    {
        $encoded = unicode::utf16Encode($value);

        $hex = '';
        for ($idx = 0, $len = count($encoded); $idx < $len; $idx++)
        {
            $hex .= strings::toHexStringOfMinLength($encoded[$idx], 4);
        }

        return new PDFHexString($hex);

    }

    public function __construct($value)
    {
        $this->value = $value;
    }


    public function asBytes()
    {
        // Append a zero if the number of digits is odd. See PDF spec 7.3.4.3
        $hex = $this->value . (\strlen($this->value) % 2 === 1 ? '0' : '');
        $hexLength = \strlen($hex);

        $bytes = new ReferenceArray(\strlen($hex) / 2);

        $hexOffset = 0;
        $bytesOffset = 0;

        // Interpret each pair of hex digits as a single byte
        while ($hexOffset < $hexLength)
        {
            $byte = \intval(substr($hex, $hexOffset, 2), 16);
            $bytes[$bytesOffset] = $byte;

            $hexOffset += 2;
            $bytesOffset += 1;
        }

        return $bytes;
    }

    public function decodeText()
    {
        $bytes = $this->asBytes();
        if (unicode::hasUtf16BOM($bytes)) return unicode::utf16Decode($bytes);
        return pdfDocEncoding::pdfDocEncodingDecode($bytes);
    }

    public function decodeDate()
    {
        $text = $this->decodeText();
        $time = \strtotime($text);
        if ($time === false)
            throw new Exception('Invalid date format ' . $time);
        $date = new DateTime($time);
        return $date;
    }


}

PDFHexString::$Instance=new PDFHexString(null);