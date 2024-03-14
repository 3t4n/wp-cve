<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:55 AM
 */

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;


use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\DTO\PDFControlBaseOptions;
use rednaoformpdfbuilder\htmlgenerator\retriever\EntryRetriever;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\AreaGenerator;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\TagGenerator;
use rednaoformpdfbuilder\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use stdClass;

abstract class PDFFieldBase
{
    /** @var PDFControlBaseOptions */
    public $options;
    public $content;
    /** @var EntryRetrieverBase */
    public $entryRetriever;
    /** @var TagGenerator */
    protected $tagGenerator;

    /** @var Loader */
    public $Loader;
    /** @var AreaGenerator */
    public $AreaGenerator;

    public function __construct($loader,$areaGenerator,$options,$entryRetriever)
    {
        $this->AreaGenerator=$areaGenerator;
        $this->Loader=$loader;
        $this->tagGenerator=new TagGenerator();
        $this->options=$options;
        $this->entryRetriever = $entryRetriever;
    }


    public function GetPropertyValue($propertyName)
    {
        if(!isset($this->options->$propertyName))
            return '';
        return $this->options->$propertyName;
    }

    /**
     * @param $propertyName
     * @return stdClass[]
     */
    protected function GetArray($propertyName)
    {
        if(!isset($this->options->$propertyName)||!is_array($this->options->$propertyName))
            return array();

        return $this->options->$propertyName;
    }

    protected function GetBoolValue($propertyName)
    {
        if(!isset($this->options->$propertyName)||!is_bool($this->options->$propertyName))
            return false;

        return $this->options->$propertyName=='true';
    }

    protected function GetStyleValue($styleName)
    {
        if(!isset($this->options->Styles->$styleName))
            return '';
        return $this->options->Styles->$styleName;
    }

    protected function CreateStyleString($styleArray)
    {
        $styles='style="';
        foreach($styleArray as $name=>$value)
        {
            $styles.=htmlspecialchars($name).':'.$value.';';
        }

        $styles.='"';
        return $styles;

    }

    abstract protected function InternalGetHTML();

    public function ShouldBeHidden()
    {
        return in_array($this->options->Id,$this->AreaGenerator->PageGenerator->documentGenerator->FieldsToHide)!==false;
    }

    public function GetHTML($static=false){

        $position='absolute';
        if($static)
            $position='static';
        $html=$this->tagGenerator->StartTag('div','PDFElement '.$this->options->Type,array(
            'width'=>$this->options->Styles->Width,
            'height'=>$this->CalculateHeight($this->options->Styles->Top,$this->options->Styles->Height),
            'top'=>$this->options->Styles->Top,
            'left'=>$this->options->Styles->Left,
            'position'=> $position
        ),
            array(
                'id'=>'pdfField_'.$this->options->Id,
                'data-element-id'=>$this->options->Id
            )
        );

        $html.=$this->tagGenerator->StartTag('div','elementContent pdfField_'.$this->options->Id,null,null);
        $html.= $this->InternalGetHTML();
        $html.='</div>';
        $html.="</div>";
        return $html;

    }

    private function CalculateHeight($top,$height)
    {
        if($top===''||$height=='')
            return $height;

        $newTop=floatval($top);
        $newHeight=floatval($height);

        if($newHeight==0)
            return $height;

        if(ceil($newTop+$newHeight)>=$this->AreaGenerator->PageGenerator->documentGenerator->options->DocumentSettings->Height)
            return ($newHeight-2).'px';
    }


}