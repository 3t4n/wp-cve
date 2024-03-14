<?php

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;

/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFSeparator extends PDFFieldBase
{

    protected function InternalGetHTML()
    {
        $orientation=$this->GetPropertyValue('Orientation');
        $styles=array();
        $attributes=array();
        if($orientation=='Horizontal')
        {
            $styles['width']='100%';
            $styles['border-top']=$this->GetPropertyValue('BorderStyle').' '.$this->GetPropertyValue('BorderSize')
                .' '.$this->GetPropertyValue('BorderColor');
        }else{
            $styles['height']=$this->options->Styles->Height;
            $styles['border-left']=$this->GetPropertyValue('BorderStyle').' '.$this->GetPropertyValue('BorderSize')
                .' '.$this->GetPropertyValue('BorderColor');
        }


        return $this->tagGenerator->StartTag('div','rule',$styles,$attributes).'</div>';
    }
}