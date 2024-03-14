<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem\Streams;


use rnpdfimporter\JPDFGenerator\JSONItem\DictionaryObjectItem;

abstract class PDFFlatStream extends StreamJSONItemBase
{
    /** @var DictionaryObjectItem */
    public $Encode;
    public function __construct($generator, $parent, $dict,$encode)
    {
        parent::__construct($generator, $parent, null);
        $this->Dict=$dict;
        $this->Encode=$encode;
    }


    public function StreamToText()
    {
        // TODO: Implement StreamToText() method.
    }

    public function ComputeContents()
    {
        $unencodedContents=$this->getUnencodedContents();
        return $unencodedContents;
    }

    protected abstract function getUnencodedContents();

}