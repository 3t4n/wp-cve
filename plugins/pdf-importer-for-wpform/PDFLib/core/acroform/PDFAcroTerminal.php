<?php


namespace rnpdfimporter\PDFLib\core\acroform;


use Error;
use Exception;
use rnpdfimporter\PDFLib\core\annotation\PDFWidgetAnnotation;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFName;

class PDFAcroTerminal extends PDFAcroField
{
    public static function fromDict($dict, $ref)
    {
        new PDFAcroTerminal($dict, $ref);
    }

    /**
     * @return PDFName
     */
    public function FT()
    {
        $nameOrRef = $this->getInheritableAttribute(PDFName::of('FT'));
        return $this->dict->context->lookup($nameOrRef, PDFName::class);
    }


    public function getWidgets()
    {
        $kidDicts = $this->Kids();

        // This field is itself a widget
        if (!$kidDicts) return [PDFWidgetAnnotation::fromDict($this->dict)];

        // This field's kids are its widgets
        $widgets = ReferenceArray::withSize($kidDicts->size());
        for ($idx = 0, $len = $kidDicts->size(); $idx < $len; $idx++)
        {
            $dict = $kidDicts->lookup($idx, PDFDict::class);
            $widgets[$idx] = PDFWidgetAnnotation::fromDict($dict);
        }

        return $widgets;
    }

    public function addWidget($ref)
    {
        $Kids = $this->normalizedEntries()['Kids'];
        $Kids[] = $ref;
    }

    public function removeWidget($idx)
    {
        $kidDicts = $this->Kids();

        if (!$kidDicts)
        {
            // This field is itself a widget
            if ($idx !== 0) throw new Exception('Index out of bounds');
            $this->setKids([]);
        } else
        {
            // This field's kids are its widgets
            if ($idx < 0 || $idx > $kidDicts->size())
            {
                throw new Exception('Index out of bounds');
            }
            $kidDicts->remove($idx);
        }
    }

    public function normalizedEntries()
    {
        $Kids = $this->Kids();

        // If this field is itself a widget (because it was only rendered once in
        // the document, so the field and widget properties were merged) then we
        // add itself to the `Kids` array. The alternative would be to try
        // splitting apart the widget properties and creating a separate object
        // for them.
        if (!$Kids)
        {
            $Kids = $this->dict->context->obj([$this->ref]);
            $this->dict->set(PDFName::of('Kids'), $Kids);
        }

        return array(
            "Kids" => $Kids
        );
    }


}