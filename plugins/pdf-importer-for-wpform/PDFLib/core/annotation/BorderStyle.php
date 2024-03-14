<?php


namespace rnpdfimporter\PDFLib\core\annotation;


use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;

class BorderStyle
{
    /** @var PDFDict */
    public $dict;

    public static function fromDict($dict)
    {
        return new BorderStyle($dict);
    }

    public function __construct($dict)
    {
        $this->dict = $dict;
    }

    public function W()
    {
        $W = $this->dict->lookup(PDFName::of('W'));
        if ($W instanceof PDFNumber) return $W;
        return null;
    }

    public function getWidth()
    {
        $number = $this->W();
        if ($number == null)
            return 1;

        $number = $number->asNumber();
        if ($number == null)
            return 1;

        return $number;
    }

    public function setWidth($width)
    {
        $W = $this->dict->context->obj($width);
        $this->dict->set(PDFName::of('W'), $W);
    }
}