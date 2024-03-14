<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem\Streams;


use rnpdfimporter\JPDFGenerator\Appearance\Operations\PDFOperation;

class ContentStreamJSONItem extends PDFFlatStream
{

    /** @var PDFOperation[] */
    public $Operations;
    public function __construct($generator, $parent, $dict,$operations,$encode=true)
    {
        parent::__construct($generator, $parent,$dict,$encode);
        $this->Operations=$operations;

    }


    public function StreamToText()
    {
        return $this->getUnencodedContents();
    }

    public static function Create($generator,$parent, $appearanceOperators,$dic)
    {
        return new ContentStreamJSONItem($generator,$parent,$dic,$appearanceOperators);

    }

    protected function getUnencodedContents()
    {
        $str='';
        foreach($this->Operations as $currentOperation)
        {
            $str.=$currentOperation->ToText()."\n";
        }
        return $str;
    }


}