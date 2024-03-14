<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields;


use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\EntryItemBase;
use rnpdfimporter\JPDFGenerator\JSONItem\StringJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\FieldJSONItem;

class ChoicePDFField extends FieldJSONItem
{

    public function GetType()
    {
        return 'Ch';
    }

    public function SetFieldValue($formField)
    {
        $value='';
        if($formField!=null)
            $value=$formField->GetText();
        $this->SetValue('/V',StringJSONItem::CreateFromText($this->Generator,$this, $value));
        $this->RemoveKey('/AP');
    }
}