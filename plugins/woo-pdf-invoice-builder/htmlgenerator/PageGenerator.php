<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/22/2018
 * Time: 11:17 AM
 */

namespace rnwcinv\htmlgenerator;


class PageGenerator
{
    /** @var PageOptionsDTO */
    private $pageOptions;
    private $orderValueRetriever;
    private $index;
    /** @var DocumentGenerator */
    public $DocumentGenerator;

    public function __construct($documentGenerator,$pageOptions,$orderValueRetriever,$index)
    {
        $this->DocumentGenerator=$documentGenerator;
        $this->pageOptions = $pageOptions;
        $this->orderValueRetriever = $orderValueRetriever;
        $this->index = $index;
    }



    public function Generate()
    {
        $styles='';
        if($this->index>0)
            $styles='style="page-break-before: always;"';
        $html='<div '.$styles.'>';


        if(!isset($this->DocumentGenerator->options->containerOptions->hideHeader)||!$this->DocumentGenerator->options->containerOptions->hideHeader)
            $html.=$this->GenerateArea($this->pageOptions->headerOptions,$this->GetFieldsByTarget('header'));

        $html.=$this->GenerateArea($this->pageOptions->contentOptions,$this->GetFieldsByTarget('content'));

        if(!isset($this->DocumentGenerator->options->containerOptions->hideFooter)||!$this->DocumentGenerator->options->containerOptions->hideFooter)
            $html.=$this->GenerateArea($this->pageOptions->footerOptions,$this->GetFieldsByTarget('footer'));

        $html.='</div>';
        return $html;
    }


    public function GetFieldsByTarget($target){
        $fields=array();
        foreach($this->pageOptions->fields as $field)
        {
            if($field->targetId==$target)
                $fields[]=$field;
        }

        return $fields;
    }

    private function GenerateArea($options, $fields)
    {
        $areaGenerator=new AreaGenerator($options,$fields,$this->orderValueRetriever,$this);
        return $areaGenerator->Generate();
    }

}