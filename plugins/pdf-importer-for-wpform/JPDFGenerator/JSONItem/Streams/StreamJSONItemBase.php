<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem\Streams;


use rnpdfimporter\JPDFGenerator\JSONItem\DictionaryObjectItem;
use rnpdfimporter\JPDFGenerator\JSONItem\JSONItemBase;
use rnpdfimporter\PDFLib\core\objects\PDFDict;

abstract class StreamJSONItemBase extends JSONItemBase
{
    /** @var PDFDict */
    public $Dict;
    public function __construct($generator, $parent,$data)
    {
        parent::__construct($generator, $parent,$data);
        $this->Dict=new DictionaryObjectItem($generator, $this,$this->GetFromData('Dict',array()));
    }


    public function InternalGetText()
    {
        $str=$this->Dict->GetText(0);
        $str.="\nstream\n";
        $str.=$this->StreamToText();
        $str.="\nendstream";
        return $str;
    }

    public abstract function StreamToText();
}