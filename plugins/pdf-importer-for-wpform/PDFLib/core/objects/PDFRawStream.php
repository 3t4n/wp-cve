<?php


namespace rnpdfimporter\PDFLib\core\objects;


use rnpdfimporter\PDFLib\utils\arrays;

class PDFRawStream extends PDFStream
{
    public $contents;


    public static function of($dict,$contents)
    {
        return new PDFRawStream($dict,$contents);
    }

    public function __construct($dict,$contents)
    {
        parent::__construct($dict);
        $this->contents=$contents;
    }

    public function asUint8Array(){
        return $this->contents->slice();
    }

    public function _clone($context)
    {
        return PDFRawStream::of($this->dict.clone($context), $this->contents->slice());
    }

    public function getContentsString(){
        return arrays::arrayAsString($this->contents);
    }

    public function getContents(){
        return $this->contents;
    }

    public function getContentsSize(){
        return count($this->contents);
    }
}