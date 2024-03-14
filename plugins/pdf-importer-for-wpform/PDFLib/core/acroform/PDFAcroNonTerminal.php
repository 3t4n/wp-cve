<?php


namespace rnpdfimporter\PDFLib\core\acroform;


use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\PDFContext;
use stdClass;

class PDFAcroNonTerminal extends PDFAcroField
{
    public static function fromDict($dict, $ref)
    {
        return new PDFAcroNonTerminal($dict, $ref);
    }

    /**
     * @param $context PDFContext
     */
    public static function create($context)
    {
        $dict = $context->obj(new stdClass());
        $ref = $context->register($dict);
        return new PDFAcroNonTerminal($dict, $ref);
    }

    public  function addField($field)
    {
        $entry = $this->normalizedEntries();
        $Kids = $entry['Kids'];
        if ($Kids != null)
            $Kids[] = $field;
    }

    public function normalizedEntries()
    {
        $Kids = $this->Kids();

        if (!$Kids)
        {
            $Kids = $this->dict->context->obj([]);
            $this->dict->set(PDFName::of('Kids'), $Kids);
        }

        return array("Kids" => $Kids);
    }

}