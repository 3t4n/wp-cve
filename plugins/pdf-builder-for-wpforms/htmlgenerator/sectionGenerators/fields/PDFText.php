<?php

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;


use rednaoformpdfbuilder\pr\Manager\TagManager;

/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFText extends PDFFieldBase
{

    protected function InternalGetHTML()
    {
        $label=$this->GetPropertyValue('Label');
        $value=$this->GetPropertyValue('Text');

        if($this->Loader->IsPR())
        {
            $tagManager=new TagManager($this->entryRetriever);
            $value=$tagManager->Process($value);
        }

        $field='<div style="width:100%">';

        $textColumn='<div class="TextValue"><p>'.$value.'</p></div>';
        $labelColumn='';
        if($label=='')
            return $field.$textColumn.'</div>';



        $position=$this->GetPropertyValue('LabelPosition');
        switch($position)
        {
            case 'Top':
                $labelColumn='<div class="TextLabel Top"><p>'.$label.'</p></div>';
                return $field.$labelColumn.$textColumn.'</div>';
                break;
            case 'Bottom':
                $labelColumn='<div class="TextLabel Top"><p >'.$label.'</p></div>';
                return $field.$textColumn.$labelColumn.'</div>';
                break;
            case 'Left':
                $labelColumn='<div class="TextLabel Left"><p >'.$label.'</p></div>';
                return $field.$labelColumn.$textColumn.'</div>';
                break;
            case 'Right':
                $labelColumn='<div class="TextLabel Right"><p >'.$label.'</p></div>';
                return $field.$textColumn.$labelColumn.'</div>';
                break;
        }











        $text=$this->tagGenerator->StartTag('p','',array('vertical-align'=>'top'),null);
        $text.=' '. nl2br($this->GetPropertyValue('Text'));
        $text.=' </p>';
        return $text;
    }
}