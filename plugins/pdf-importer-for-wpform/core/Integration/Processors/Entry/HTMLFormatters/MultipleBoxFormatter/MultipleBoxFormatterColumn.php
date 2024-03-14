<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter;



use rnpdfimporter\core\htmlgenerator\sectionGenerators\HtmlTagWrapper;

class MultipleBoxFormatterColumn
{
    public $Label;
    public $Value;
    public $ColSpan;
    public $Width;

    /** @var MultipleBoxFormatterRow */
    public $Row;
    public function __construct($row,$label,$value,$width=0,$colSpan=1)
    {
        $this->Row=$row;
        $this->Label=$label;
        $this->Value=$value;
        $this->Width=$width;
        $this->ColSpan=$colSpan;
    }

    public function GetTotalMaxNumberOfColumns(){
        return $this->Row->GetTotalMaxNumberOfColumns();
    }

    public function IsLastColumnOfRow(){
        $index=\array_search($this,$this->Row->Columns);
        return $index==count($this->Row->Columns)-1;
    }

    /**
     * @param $generator HtmlTagWrapper
     */
    public function CreateHtml($generator)
    {
        $colspanToUse=$this->ColSpan;
        if($this->IsLastColumnOfRow())
        {
            $totalColumnsUsed=0;
            foreach($this->Row->Columns as $currentColumn)
                $totalColumnsUsed+=$currentColumn->ColSpan;

            $colspanToUse+=$this->GetTotalMaxNumberOfColumns()-$totalColumnsUsed;
        }


       $column=$generator->CreateAndAppendChild('td');
       $column->SetAttribute('colspan',$colspanToUse);
       $column->AddStyle('width',$this->Width.'%');
       $column->AddStyle('margin-top','7px');
       $label=$column->CreateAndAppendChild('div');
       $label->SetText($this->Label);
       $label->AddStyle('margin-bottom','5px');


       $value=$column->CreateAndAppendChild('div');
       $value->AddStyle('width','100%');
       $value->AddStyle('border','1px solid black');
       $value->AddStyle('padding','3px');
       if(trim($this->Value)!='')
            $value->SetText($this->Value);
        else
            $value->SetText("\xc2\xa0");


    }


}
