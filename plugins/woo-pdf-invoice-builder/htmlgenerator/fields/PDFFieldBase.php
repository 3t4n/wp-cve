<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:55 AM
 */

namespace rnwcinv\htmlgenerator\fields;

use rnwcinv\htmlgenerator\FieldDTO;
use rnwcinv\htmlgenerator\OrderValueRetriever;
use rnwcinv\htmlgenerator\TagGenerator;
use stdClass;

abstract class PDFFieldBase
{
    /** @var FieldDTO */
    public $options;
    public $content;
    /** @var OrderValueRetriever */
    public $orderValueRetriever;
    /** @var TagGenerator */
    protected $tagGenerator;


    public function __construct($options,$orderValueRetriever)
    {
        $this->tagGenerator=new TagGenerator();
        $this->options=$options;
        $this->orderValueRetriever = $orderValueRetriever;
    }


    protected function GetPropertyValue($propertyName)
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

    protected function GetAssoc($propertyName)
    {
        if(!isset($this->options->$propertyName)||!\is_object($this->options->$propertyName))
            return new stdClass();

        return $this->options->$propertyName;
    }
    public function GetBoolValue($propertyName)
    {
        if(!isset($this->options->$propertyName)||!is_bool($this->options->$propertyName))
            return false;

        return $this->options->$propertyName=='true';
    }

    protected function GetStyleValue($styleName)
    {
        if(!isset($this->options->styles->$styleName))
            return '';
        return $this->options->styles->$styleName;
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

    public function GetHTML(){

        if(!$this->orderValueRetriever->useTestData&&isset($this->options->Conditions)
            &&count($this->options->Conditions)>0
            &&\RednaoWooCommercePDFInvoice::IsPR())
        {

            require_once \RednaoWooCommercePDFInvoice::$DIR.'pr/conditions/ConditionManager.php';
            $manager=new \ConditionManager($this->orderValueRetriever);
            if($manager->ShouldProcess((object)array('conditionList'=>$this->options->Conditions), null ))
            {
                return '';
            }

        }

        if(isset($this->options->Expandable)&&$this->options->Expandable==true)
        {
            if(isset($this->options->styles)&&isset($this->options->styles->height))
            {
                unset($this->options->styles->height);
            }
        }

        if(isset($this->options->styles)&&isset($this->options->styles->height)&&$this->options->styles->height=='')
            unset($this->options->styles->height);

        if(isset($this->options->styles)&&isset($this->options->styles->width)&&$this->options->styles->width=='')
            unset($this->options->styles->width);

        $html=$this->tagGenerator->StartTag('div','PDFElement',$this->options->styles,
            array(
                'id'=>'pdfField_'.$this->options->fieldID,
                'data-element-id'=>$this->options->fieldID
            )
        );

        $html.=$this->tagGenerator->StartTag('div','elementContent',null,null);
        $html.= $this->InternalGetHTML();
        $html.='</div>';
        $html.="</div>";
        return $html;

    }


}