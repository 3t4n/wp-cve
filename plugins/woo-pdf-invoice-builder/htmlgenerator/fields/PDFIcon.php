<?php

namespace  rnwcinv\htmlgenerator\fields;



/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFIcon extends PDFFieldBase
{

    protected function InternalGetHTML()
    {
        $fontSize=floatval($this->GetPropertyValue('FontSize'));
        if($fontSize=='')
            $fontSize=20;
        $styles=array();
        $attrs=array();
        $iconData=$this->GetPropertyValue('IconData');
        $styles['vertical-align']='top';
        $styles['font-family']=$iconData->font.' !important';
        $text='';


        if($this->GetPropertyValue('IconStyle')==''||$this->GetPropertyValue('IconStyle')=='basic')
        {
            $styles['font-size']=$fontSize.'px !important';
            $styles['color']=$this->GetPropertyValue('Color').' !important';
            return $this->tagGenerator->StartTag('span','',$styles,$attrs).$iconData->value.'</span>';
        }

        if($this->GetPropertyValue('IconStyle')=='round'||$this->GetPropertyValue('IconStyle')=='square')
        {

            $styles['font-size']=ceil($fontSize*.63).' px !important';
            $styles['line-height']=ceil($fontSize*.63).' px !important';
            $styles['color']='#ffffff !important';
            $styles['position']='absolute';
            $styles['top']=0;
            $styles['left']=0;
            $styles['opacity']=1;

            $height=$this->GetPropertyValue('OuterHeight');
            $width=$this->GetPropertyValue('OuterWidth');
            if($height!=''&&$width!='')
            {
                $styles['top']=($fontSize/2-$height/2).'px';
                $styles['left']=($fontSize/2-$width/2).'px';

            }

            $text=$this->tagGenerator->StartTag('div','',array('width'=>$fontSize.'px','height'=>$fontSize.'px','position'=>'relative'),'');
            $text.=$this->tagGenerator->StartTag('img','',array('width'=>$fontSize.'px','height'=>$fontSize.'px'),array('src'=>'data:image/svg+xml;base64,'.$this->GetPropertyValue('Data'))).'</img>';
            $text.= $this->tagGenerator->StartTag('span','',$styles,$attrs).$iconData->value.'</span>';
            $text.='</div>';
            return $text;



        }

    }
}