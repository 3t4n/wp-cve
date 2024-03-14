<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 7:33 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters;


class MultipleLineFormatter  extends PHPFormatterBase
{
    private $lines;
    public $SingleLine=false;
    public $IsInline=false;
    public $Columns=0;
    public function __construct($templateField=null)
    {
        parent::__construct($templateField);
        $this->lines=[];
    }

    public function SetIsInline($columns=0)
    {
        $this->IsInline=true;
        $this->Columns=$columns;
    }

    public function AddLine($line)
    {
        $this->lines[]=$line;
    }


    public function __toString()
    {
        if($this->TemplateField!=null&&$this->TemplateField->GetPropertyValue('OptionsAlignment')=='horizontal')
        {
            $columns=max(0, intval($this->TemplateField->GetPropertyValue('Columns')));
            if($columns==0)
                return implode(', ',$this->lines);

            $html='<table>';
            for($i=0;$i<count($this->lines);$i+=$columns)
            {
                $html.='<tr>';
                for($j=0;$j<$columns;$j++)
                {
                    if($i+$j<count($this->lines))
                        $html.='<td style="padding: 3px">'.esc_attr($this->lines[$i+$j]).'</td>';
                    else
                        $html.='<td></td>';
                }
                $html.='</tr>';

            }
            $html.='</table>';
            return $html;
        }

        if($this->SingleLine)
        {
            $text='<table>';
            for($i=0;$i+1<count($this->lines);$i+=2)
            {
                $text.='<tr><td style="padding: 3px">'.esc_attr($this->lines[$i]).'</td><td  style="padding: 3px">'.esc_attr($this->lines[$i+1]).'</td></tr>';
            }
            $text.='</table>';

            return $text;
        }

        $text='';
        foreach($this->lines as $line)
            $text.= '<p style="margin-bottom: 3px">'.esc_html($line).'</p>';

        return $text;
    }

    public function IsEmpty(){
        return \count($this->lines)==0;
    }


    public function ToText()
    {
        return implode(', ',$this->lines);
    }

    public function SetSingleLine()
    {
        $this->SingleLine=true;
    }
}