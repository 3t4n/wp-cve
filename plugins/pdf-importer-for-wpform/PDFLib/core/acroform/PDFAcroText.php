<?php


namespace rnpdfimporter\PDFLib\core\acroform;


use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFHexString;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;
use rnpdfimporter\PDFLib\core\objects\PDFString;
use rnpdfimporter\PDFLib\core\PDFContext;

class PDFAcroText extends PDFAcroTerminal
{
    public static function fromDict($dict, $ref)
    {
        return new PDFAcroText($dict, $ref);
    }

    /**
     * @param $context PDFContext
     */
    public static function create($context)
    {
        $dict = $context->obj((object)array(
            'FT' => 'Tx',
            'Kids' => new ReferenceArray()
        ));

        $ref = $context->register($dict);
        return new PDFAcroText($dict, $ref);
    }

    public function MaxLen()
    {
        $maxLen = $this->dict->lookup(PDFName::of('MaxLen'));
        if ($maxLen instanceof PDFNumber) return $maxLen;
        return null;
    }

    public function Q()
    {
        $q = $this->dict->lookup(PDFName::of('Q'));
        if ($q instanceof PDFNumber) return $q;
        return null;
    }

    public function setMaxLength($maxLength)
    {
        $this->dict->set(PDFName::of('MaxLen'), PDFNumber::of($maxLength));
    }

    public function removeMaxLength()
    {
        $this->dict->delete(PDFName::of('MaxLen'));
    }

    public function getMaxLength()
    {
        $len = $this->MaxLen();
        if ($len == null)
            return null;

        return $len->asNumber();
    }

    public function setQuadding($quadding)
    {
        $this->dict->set(PDFName::of('Q'), PDFNumber::of($quadding));
    }

    public function getQuadding()
    {
        $q = $this->Q();
        if ($q == null)
            return null;
        return $q->asNumber();
    }

    public function setValue($value)
    {
        $this->dict->set(PDFName::of('V'), $value);
    }

    public function removeValue() {
        $this->dict->delete(PDFName::of('V'));
    }

    public function getValue() {
        $v = $this->V();
        if ($v instanceof PDFString || $v instanceof PDFHexString) return $v;
        return null;
    }
}