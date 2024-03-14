<?php


namespace rednaoformpdfbuilder\htmlgenerator\tableCreator;


class HTMLTableCreator
{
    private $table;
    private $tHead='';
    private $tFoot='';
    private $tBody;

    private $lastSectionCreated;
    private $lastRow;

    private function FormatStyles($styles){
        $styleStr='';
        if($styles!=null)
            foreach($styles as $styleName=>$value){
                if($value==null)
                    continue;
                $styleStr.=$styleName.':'.$value.';';
            }

        return $styleStr;
    }
    public function __construct($classes,$styles='')
    {
        $this->table='<table style="border-collapse:collapse" class="'.$classes.'" style="'.$styles.'">';
        $this->tHead='';
        $this->tBody='';
        $this->lastRow='';

    }

    public function CreateTHead($classes=''){
        if($this->lastRow!='')
        {
            $this->lastSectionCreated .= $this->lastRow . '</tr>';
            $this->lastRow='';
        }
        $this->tHead='<thead class="'.$classes.'">';
        $this->lastSectionCreated=&$this->tHead;
    }

    public function CreateTFoot($classes=''){
        if($this->lastRow!='')
        {
            $this->lastSectionCreated .= $this->lastRow . '</tr>';
            $this->lastRow='';
        }
        $this->tFoot='<tfoot class="'.$classes.'">';
        $this->lastSectionCreated=&$this->tFoot;
    }

    public function CreateTBody($classes=''){
        if($this->lastRow!='')
        {
            $this->lastSectionCreated .= $this->lastRow . '</tr>';
            $this->lastRow='';
        }
        $this->tBody.='<tbody class="'.$classes.'">';
        $this->lastSectionCreated=&$this->tBody;
    }


    public function CreateRow($classes=''){
        if($this->lastRow!='')
        {
            $this->lastSectionCreated .= $this->lastRow . '</tr>';
        }

        $this->lastRow='<tr class="'.$classes.'">';
    }


    public function CreateTextColumn($text,$classes,$columnType='td',$styles='')
    {
        $styles=$this->FormatStyles($styles);
        $column='';
        if($columnType=='th')
            $column='<th class="'.$classes.'"  style="'.$styles.'">';
        else
            $column='<td class="'.$classes.'" style="'.$styles.'">';

        $column.='<p>'.\esc_html($text).'</p>';
        if($columnType=='th')
            $column.='</th>';
        else
            $column.='</td>';

        $this->lastRow.=$column;
    }

    public function GetHTML()
    {
        if($this->lastRow!='')
            $this->lastSectionCreated.=$this->lastRow.'</tr>';

        if($this->tHead!='')
            $this->tHead.='</thead>';

        if($this->tBody!='')
            $this->tBody.='</tbody>';

        if($this->tFoot!='')
            $this->tFoot.='</tfoot>';



        return $this->table.$this->tHead.$this->tBody.$this->tFoot. '</table>';
    }

    public function CreateRawColumn($text,$classes,$columnType='td',$styles='')
    {
        $styles=$this->FormatStyles($styles);
        $column='';
        if($columnType=='th')
            $column='<th class="'.$classes.'" style="'.$styles.'">';
        else
            $column='<td class="'.$classes.'" style="'.$styles.'">';

        $column.=$text;
        $column.='</td>';

        $this->lastRow.=$column;
    }


}