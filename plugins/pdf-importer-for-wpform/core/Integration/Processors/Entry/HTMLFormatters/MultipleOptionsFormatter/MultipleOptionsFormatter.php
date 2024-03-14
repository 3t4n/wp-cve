<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleOptionsFormatter;


use rnpdfimporter\core\htmlgenerator\sectionGenerators\HtmlTagWrapper;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;

class MultipleOptionsFormatter extends PHPFormatterBase
{
    public $Type;
    /** @var MultipleOptionsItems[] */
    public $Items;

    public function __construct($type)
    {
        $this->Type=$type;
        $this->Items=[];
    }

    public function AddOption($label,$isSelected)
    {
        if($this->HasOption($label))
            return;

        $this->Items[]=new MultipleOptionsItems($label,$isSelected);
    }

    public function HasOption($label)
    {
        foreach($this->Items as $currentItem)
        {
            if($currentItem->Label==$label)
                return true;
        }

        return false;
    }


    public function __toString()
    {
        $tagGenerator=new HtmlTagWrapper('table');
        $body=$tagGenerator->CreateAndAppendChild('tbody');
        foreach($this->Items as $currentItem)
        {
            $row=$body->CreateAndAppendChild('tr');
            $this->CreateIcon($row,$currentItem->IsSelected);

            $column=$row->CreateAndAppendChild('td');
            $column->AddStyle('padding','5px;');
            $column->SetText($currentItem->Label);


        }

        $html= $tagGenerator->Document->saveHTML();

        return $html;
    }

    public function IsEmpty()
    {
        return count($this->Items)==0;
    }

    public function ToText()
    {
        // TODO: Implement ToText() method.
    }

    /**
     * @param $item HtmlTagWrapper
     * @param $IsSelected
     */
    private function CreateIcon($item, $IsSelected)
    {
        $column=$item->CreateAndAppendChild('td');
        $column->AddStyle('width','10px');
        $column->AddStyle('vertical-align','middle');
       // $column->AddStyle('background-color','red');

        $span=$column->CreateAndAppendChild('span');
        $span->AddStyle('font-family','FontAwesome');
        $span->AddStyle('color','black');
        $span->AddStyle('font-size','18px');
        $span->AddStyle('line-height','18px');

        if($this->Type==MultipleOptionsFormatterType::$Radio)
        {
            if($IsSelected)
            {
                $span->SetHtml('&#xf192;');
            }else
                $span->SetHtml('&#xf10c;');
        }else
        {
            if($IsSelected)
                $span->SetHtml('&#xf046;');
            else
                $span->SetHtml('&#xf096;');
        }


    }
}

class MultipleOptionsItems{
    public $Label;
    public $IsSelected;

    public function __construct($label,$isSelected)
    {
        $this->Label=$label;
        $this->IsSelected=$isSelected;
    }


}

class MultipleOptionsFormatterType{
    public static $Checkbox='checkbox';
    public static $Radio='radio';
}