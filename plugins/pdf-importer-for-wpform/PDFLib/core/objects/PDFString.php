<?php


namespace rnpdfimporter\PDFLib\core\objects;


use DateTime;
use Exception;
use rnpdfimporter\js\src\lib\PDFLib\utils\pdfDocEncoding;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\syntax\CharCodes;
use rnpdfimporter\PDFLib\utils\strings;
use rnpdfimporter\PDFLib\utils\unicode;

class PDFString extends PDFObject
{
    public $value;
    public static $Instance;

    public static function of($value)
    {
        return new PDFString($value);
    }

    /**
     * @param $date DateTime
     */
    public static function fromDate($date)
    {
        return new PDFString('D:' . $date->format('Ymdhis') . 'Z');
    }

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function asBytes()
    {
        $bytes = new ReferenceArray();

        $octal = '';
        $escaped = false;

        $pushByte = function ($byte = null) use ($bytes, &$escaped) {
            if ($byte !== null) $bytes[] = $byte;
            $escaped = false;
        };

        for ($idx = 0, $len = \strlen($this->value); $idx < $len; $idx++)
        {
            $char = $this->value[$idx];
            $byte = strings::toCharCode($char);
            $nextChar = $this->value[$idx + 1];
            if (!$escaped)
            {
                if ($byte === CharCodes::BackSlash) $escaped = true;
                else $pushByte($byte);
            } else
            {
                if ($byte === CharCodes::Newline) $pushByte();
                else if ($byte === CharCodes::CarriageReturn) $pushByte();
                else if ($byte === CharCodes::n) $pushByte(CharCodes::Newline);
                else if ($byte === CharCodes::r) $pushByte(CharCodes::CarriageReturn);
                else if ($byte === CharCodes::t) $pushByte(CharCodes::Tab);
                else if ($byte === CharCodes::b) $pushByte(CharCodes::Backspace);
                else if ($byte === CharCodes::f) $pushByte(CharCodes::FormFeed);
                else if ($byte === CharCodes::LeftParen) $pushByte(CharCodes::LeftParen);
                else if ($byte === CharCodes::RightParen) $pushByte(CharCodes::RightParen);
                else if ($byte === CharCodes::Backspace) $pushByte(CharCodes::BackSlash);
                else if ($byte >= CharCodes::Zero && $byte <= CharCodes::Seven)
                {
                    $octal += $char;
                    if (\strlen($octal) === 3 || !($nextChar >= '0' && $nextChar <= '7'))
                    {
                        $pushByte(\intval($octal, 8));
                        $octal = '';
                    }
                } else
                {
                    $pushByte($byte);
                }
            }
        }

        return ReferenceArray::createFromArray($bytes);
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
        if ($time == false)
            throw new Exception('Invalid pdf date');

        $date = new DateTime($time);

        return $date;
    }

    public function  asString() {
        return $this->value;
    }

    public function _clone($context)
    {
        return PDFString::of($this->value);
    }

    public function __toString()
    {
        return '('.$this->value.')';
    }

    public function sizeInBytes()
    {
        return \strlen($this->value);
    }

    public function copyBytesInto($buffer, $offset)
    {
        $buffer[$offset++] = CharCodes::LeftParen;
        $offset += strings::copyStringIntoBuffer($this->value, $buffer, $offset);
        $buffer[$offset++] = CharCodes::RightParen;
        return \strlen($this->value) + 2;
    }


}

PDFString::$Instance=new PDFString(null);