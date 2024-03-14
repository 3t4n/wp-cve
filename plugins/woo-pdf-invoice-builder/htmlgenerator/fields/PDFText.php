<?php

namespace  rnwcinv\htmlgenerator\fields;



use RednaoWooCommercePDFInvoice;
use rnwcinv\pr\Manager\TagManager;

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
        $text=$this->tagGenerator->StartTag('p','',array('vertical-align'=>'top'),null);
        $text.=' '.$this->orderValueRetriever->TranslateText($this->options->fieldID,'text',$this->GetPropertyValue('Text'));
        $text.=' </p>';

        if(RednaoWooCommercePDFInvoice::IsPR())
        {
            $tag=new TagManager($this->orderValueRetriever);
            $text=$tag->Process($text);
        }
        return $text;
    }
}