<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields;


use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\EntryItemBase;
use rnpdfimporter\JPDFGenerator\Appearance\AppearanceBase;
use rnpdfimporter\JPDFGenerator\Appearance\DefaultTextAppearance;
use rnpdfimporter\JPDFGenerator\JSONItem\StringJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\FieldJSONItem;

class TextPDFField extends FieldJSONItem
{

    public function GetType()
    {
        return 'Txt';
    }

    public function GetRealValue($key)
    {
        $value=$this->GetValue($key);
        if($value==null)
        {
            return null;
        }

        $text=$value->GetText(0);
        if(strlen($text)>1&&$text[0]=="(")
        {
            return substr($text,1,strlen($text)-2);
        }

        return $value;
    }

    public function SetFieldValue($formField)
    {
        $appearance=new DefaultTextAppearance($this);
        $value='';
        if($formField!=null)
            $value=apply_filters("rnpdfimporter_format_value",$formField->GetText(),$formField);

        $appearance->Generate($value);
        $parent=$this->GetParent();
        $objectToUpdate=$this;
        if($parent!=null)
            $objectToUpdate=$parent;
        $objectToUpdate->SetValue('/V',StringJSONItem::CreateFromText($this->Generator,$this, $value));
        //$this->RemoveKey('/AP');
    }

}