<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/22/2018
 * Time: 11:17 AM
 */

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators;


use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\DTO\PageOptions;


class PageGenerator
{
    /** @var PageOptions */
    private $pageOptions;
    private $orderValueRetriever;
    private $index;
    /** @var Loader */
    public $Loader;
    /** @var DocumentGenerator */
    public $documentGenerator;
    public $IsEmpty;

    public function __construct($loader,$documentGenerator,$pageOptions,$orderValueRetriever,$index)
    {
        $this->IsEmpty=true;
        $this->documentGenerator=$documentGenerator;
        $this->Loader=$loader;
        $this->pageOptions = $pageOptions;
        $this->orderValueRetriever = $orderValueRetriever;
        $this->index = $index;
    }

    public function Generate()
    {
        if($this->pageOptions==null)
            return '';
        $styles='';
        if($this->index>0)
            $styles='style="page-break-before: always;"';
        $html='<div class="pdfPage" '.$styles.'>';
        if(isset($this->documentGenerator->options->DocumentSettings->HideHeader)&&!$this->documentGenerator->options->DocumentSettings->HideHeader)
            $html.=$this->GenerateArea($this->pageOptions->HeaderSection,$this->GetFieldsByTarget('header'));
        $html.=$this->GenerateArea($this->pageOptions->ContentSection,$this->GetFieldsByTarget('content'));
        if(isset($this->documentGenerator->options->DocumentSettings->HideFooter)&&!$this->documentGenerator->options->DocumentSettings->HideFooter)
            $html.=$this->GenerateArea($this->pageOptions->FooterSection,$this->GetFieldsByTarget('footer'));
        $html.='</div>';
        if($this->IsEmpty)
            return '';
        return $html;
    }


    public function GetFieldsByTarget($target){
        if($this->pageOptions==null)
            return [];
        $fields=array();
        foreach($this->pageOptions->Fields as $field)
        {
            if($field->TargetId==$target)
                $fields[]=$field;
        }

        return $fields;
    }

    private function GenerateArea($options, $fields)
    {
        $areaGenerator=new AreaGenerator($this, $this->Loader, $options,$fields,$this->orderValueRetriever);

        $html= $areaGenerator->Generate();
        if(!$areaGenerator->IsEmpty)
            $this->IsEmpty=false;
        return $html;
    }

}