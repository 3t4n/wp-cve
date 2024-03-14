<?php


namespace rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter;


use DOMDocument;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\HtmlTagWrapper;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\TagGenerator;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;

class SingleBoxFormatter extends PHPFormatterBase
{
    public $Value;

    public function __construct($value,$field=null)
    {
        parent::__construct($field);
        $this->Value=$value;
    }


    public function __toString()
    {
        $tagGenerator=new HtmlTagWrapper('div');
        $tagGenerator->AddStyle('width','100%');
        $tagGenerator->AddStyle('border','1px solid black');
        $tagGenerator->AddStyle('padding','3px');
        if(trim($this->Value)!='')
            $tagGenerator->SetText($this->Value);
        else
            $tagGenerator->SetText("\xc2\xa0");

        $html= $tagGenerator->Document->saveHTML();

        return $html;

    }

    public function IsEmpty()
    {
        return $this->Value=='';
    }

    public function ToText()
    {
        return $this->Value;
    }
}