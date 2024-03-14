<?php


namespace rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleOptionsFormatter;


use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\HtmlTagWrapper;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;

class MultipleOptionsFormatter extends PHPFormatterBase
{
    public $Type;
    /** @var MultipleOptionsItems[] */
    public $Items;

    public function __construct($type,$field=null)
    {
        parent::__construct($field);
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
        if($this->TemplateField!=null&&$this->TemplateField->GetPropertyValue('OptionsAlignment')=='horizontal')
        {
            $columns=max(0, intval($this->TemplateField->GetPropertyValue('Columns')));
            $html='';
            $selectedItems=[];
            foreach($this->Items as $currentItem)
            {
                if($currentItem->IsSelected)
                    $selectedItems[]=$currentItem;
            }
            if($columns==0)
            {
                foreach($selectedItems as $currentItem)
                {
                    $icon='&#xf096;';
                    if($currentItem->IsSelected)
                    {
                        $icon='&#xf046;';
                    }
                    $html.='<div style="margin-right: 30px;display: inline;line-height: 25px"><span style="font-family:FontAwesome;font-size: 18px;line-height: 18px">'.$icon.'</span>'.esc_html__($currentItem->Label).'</div>';
                }

                return $html;

            }

            $html='<table>';
            for($i=0;$i<count($selectedItems);$i+=$columns)
            {
                $html.='<tr>';
                for($j=0;$j<$columns;$j++)
                {
                    if($i+$j<count($selectedItems))
                    {
                        $currentItem=$selectedItems[$i+$j];
                        $icon='&#xf096;';
                        if($currentItem->IsSelected)
                        {
                            $icon='&#xf046;';
                        }
                        $item='<div style="margin-right: 30px;display: inline;"><span style="font-family:FontAwesome;font-size: 18px;line-height: 18px">'.$icon.'</span>'.esc_html__($currentItem->Label).'</div>';
                        $html .= '<td style="padding: 3px">' . $item . '</td>';
                    }
                    else
                        $html.='<td></td>';
                }
                $html.='</tr>';

            }
            $html.='</table>';
            return $html;
        }
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