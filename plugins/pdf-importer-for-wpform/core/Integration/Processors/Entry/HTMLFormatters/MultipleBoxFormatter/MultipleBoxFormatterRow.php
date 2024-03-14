<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter;



use rnpdfimporter\core\htmlgenerator\sectionGenerators\HtmlTagWrapper;

class MultipleBoxFormatterRow
{
    /** @var MultipleBoxFormatterColumn[] */
    public $Columns;
    /** @var MultipleBoxFormatter */
    public $BoxFormatter;

    public function __construct($boxFormatter)
    {
        $this->BoxFormatter=$boxFormatter;
        $this->Columns=[];
    }

    public function GetTotalMaxNumberOfColumns(){
        return $this->BoxFormatter->GetTotalMaxNumberOfColumns();
    }

    public function GetRowIndex(){
        return \array_search($this,$this->BoxFormatter->Rows);
    }


    public function AddColumn($label,$value,$width)
    {
        $column=new MultipleBoxFormatterColumn($this,$label,$value,$width);
        $this->Columns[]=$column;
    }

    /**
     * @param $generator HtmlTagWrapper
     */
    public function CreateHtml($generator)
    {
        $div=$generator->CreateAndAppendChild('tr');


        foreach($this->Columns as $currentColumn)
        {
            $currentColumn->CreateHtml($div);
        }
    }
}