<?php


namespace rnpdfimporter\PDFLib\core\objects;


use rnpdfimporter\PDFLib\core\integration\ReferenceArray;

class PDFInvalidObject extends PDFObject
{

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function of($data)
    {
        return new PDFInvalidObject($data);
    }

    public function _clone($context)
    {
        return PDFInvalidObject::of(ReferenceArray::createFromArray($this->data));
    }

    public function __toString()
    {
        return "PDFInvalidObject(".count($this->data).' bytes)';
    }

    public function sizeInBytes()
    {
        return count($this->data);
    }

    public function copyBytesInto($buffer, $offset)
    {
        $length =count($this->data);
        for ($idx = 0; $idx < $length; $idx++) {
            $buffer[$offset++] = $this->data[$idx];
        }
        return $length;

    }


}