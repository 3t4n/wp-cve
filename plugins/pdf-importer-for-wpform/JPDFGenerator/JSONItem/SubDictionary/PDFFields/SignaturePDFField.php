<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields;


use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\EntryItemBase;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\FieldJSONItem;

class SignaturePDFField extends FieldJSONItem
{

    public function GetType()
    {
        return 'Sig';
    }

    public function SetFieldValue($formField)
    {
        // TODO: Implement SetValue() method.
    }
}