<?php

namespace  rnwcinv\htmlgenerator\fields;



/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFFigure extends PDFFieldBase
{

    protected function InternalGetHTML()
    {
        $img= $this->tagGenerator->StartTag('img','',array('width'=>$this->options->styles->width,'height'=>$this->options->styles->height),array('src'=>'data:image/svg+xml;base64,'.$this->GetPropertyValue('Data'))).'</img>';
        return $img;
    }
}