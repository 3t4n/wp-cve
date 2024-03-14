<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter;


use DOMDocument;
use rnpdfimporter\core\htmlgenerator\sectionGenerators\HtmlTagWrapper;
use rnpdfimporter\core\htmlgenerator\sectionGenerators\TagGenerator;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;

class MultipleBoxFormatter extends PHPFormatterBase
{
    /** @var MultipleBoxFormatterRow[] */
    public $Rows;

    /** @var MultipleBoxFormatterRow */
    public $CurrentRow;

    public function __construct()
    {
        $this->Rows=[];
    }

    public function CreateRow(){
        $this->CurrentRow=new MultipleBoxFormatterRow($this);
        $this->Rows[]=$this->CurrentRow;

        return $this->CurrentRow;
    }

    public function GetTotalMaxNumberOfColumns(){
        $max=0;
        foreach($this->Rows as $row)
        {
            $max=max(count($row->Columns),$max);
        }

        return $max;
    }

    public function CreateRowWithColumn($label,$value,$width=100)
    {
        $row=$this->CreateRow();
        $row->AddColumn($label,$value,$width);
    }

    public function __toString()
    {
        $tagGenerator=new HtmlTagWrapper('table');
        $body=$tagGenerator->CreateAndAppendChild('tbody');
        foreach($this->Rows as $currentRow)
        {
            $currentRow->CreateHtml($body);
        }

        $html= $tagGenerator->Document->saveHTML();

        return $html;

    }

    public function IsEmpty()
    {
        return count($this->Rows)==0;
    }

    public function ToText()
    {
        return '';
    }
}