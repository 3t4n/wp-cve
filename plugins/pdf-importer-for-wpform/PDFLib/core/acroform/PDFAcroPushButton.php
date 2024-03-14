<?php


namespace rnpdfimporter\PDFLib\core\acroform;


use rnpdfimporter\PDFLib\core\acroform\AcroButtonFlags;
use rnpdfimporter\PDFLib\core\acroform\PDFAcroButton;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\PDFContext;
use rnpdfimporter\PDFLib\utils\arrays;

class PDFAcroPushButton extends PDFAcroButton
{
    public static function fromDict($dict,$ref)
    {
        return new PDFAcroPushButton($dict,$ref);
    }

    /**
     * @param $context PDFContext
     */
    public static function create($context)
    {
        $dict=$context->obj((object)array(
           'FT'=>'BTN',
           'Ff'=>AcroButtonFlags::$PushButton,
           'Kids'=>new ReferenceArray()
        ));

        $ref=$context->register($dict);
        return new PDFAcroPushButton($dict,$ref);
    }
}