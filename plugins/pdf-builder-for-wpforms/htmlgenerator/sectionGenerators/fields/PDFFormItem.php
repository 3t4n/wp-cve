<?php

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;



use rednaoformpdfbuilder\Utils\Sanitizer;

/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFFormItem extends PDFFieldBase
{

    protected function InternalGetHTML()
    {
        $label=$this->GetPropertyValue('Label');
        $value='';

        $style='standard';
        if(isset($this->options->Style))
            $style=$this->options->Style;
        if($this->entryRetriever==null)
            $value='<p>Not available on preview</p>';
        else
        {
            $value = $this->entryRetriever->GetHtmlByFieldId($this->options->FieldId, $style,$this);
            if(($value==null||$value->IsEmpty())&&$this->GetPropertyValue("HideWhenEmpty",false))
                return '';
        }
        $field='<div style="width: 100%">';

        $labelWidth=100;
        $valueWidth=100;
        $additionalValueStyles='';

        if($this->GetPropertyValue('LabelPosition')=='Left'||$this->GetPropertyValue('LabelPosition')=='Right')
        {
            $additionalValueStyles='display:inline-block;vertical-align:top';
            if(trim($this->GetPropertyValue('Label'))=='')
                $labelWidth=0;
            else
                $labelWidth=Sanitizer::SanitizeNumber($this->GetPropertyValue('LabelWidth'),0);

            if($labelWidth==0)
                $labelWidth=30;

            $valueWidth=100-$labelWidth;


        }
        if($labelWidth==0)
            $labelWidth=0;
        $textColumn='<div class="FieldValue" style="width:'.$valueWidth.'%;'.$additionalValueStyles.'">'.$value.'</div>';
        $labelColumn='';
        if($label=='')
            return $field.$textColumn.'</div>';



        $position=$this->GetPropertyValue('LabelPosition');
        switch($position)
        {
            case 'Top':
                $labelColumn='<div class="FieldLabel Top"><p>'.$label.'</p></div>';
                return $field.$labelColumn.$textColumn.'</div>';
                break;
            case 'Bottom':
                $labelColumn='<div class="FieldLabel Top"><p >'.$label.'</p></div>';
                return $field.$textColumn.$labelColumn.'</div>';
                break;
            case 'Left':
                $labelColumn='';
                if(trim($label)!='')
                    $labelColumn='<div style="white-space: pre-wrap !important; width:'.$labelWidth.'%;display: inline-block;vertical-align: top" class="FieldLabel"><p>'.$label.'</p></div>';
                return $field.$labelColumn.$textColumn.'</div>';
                break;
            case 'Right':
                $labelColumn='<div style="white-space: pre-wrap;width:'.$labelWidth.'%;display: inline-block;vertical-align: top" class="FieldLabel Right"><p >'.$label.'</p></div>';
                return  $field.$field.$textColumn.$labelColumn.'</div>';
                break;
        }
    }




}