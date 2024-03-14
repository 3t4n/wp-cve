<?php


namespace rnpdfimporter\PDFLib\api;


use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\parser\PDFParser;
use rnpdfimporter\PDFLib\utils\arrays;

class PDFDocument
{
    public $bytes;
    public static function load($pdf)
    {
        $bytes=new ReferenceArray();
        for($i=0;$i<\strlen($pdf);$i++)
        {
            $bytes[]=ord($pdf[$i]);
        }

        $context=PDFParser::forBytesWithOptions($bytes,100,true,true)->parseDocument();


    }

    public static function loadFromPath($path)
    {
        PDFDocument::load(\file_get_contents($path));
    }
}