<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields;


use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\EntryItemBase;
use rnpdfimporter\JPDFGenerator\JSONItem\DictionaryObjectItem;
use rnpdfimporter\JPDFGenerator\JSONItem\PrecompiledStringJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\FieldJSONItem;
use rnpdfimporter\PDFLib\core\objects\PDFDict;

class ButtonPDFField extends FieldJSONItem
{
    private $checkedStateValue=null;

    public function GetType()
    {
        return 'Btn';
    }

    public function GetCheckedStateName(){
        if($this->checkedStateValue==null)
        {
            $ap = $this->GetValue('/AP');

            if($ap ==null||!$ap instanceof DictionaryObjectItem||count($ap->Dictionary)==0||!$ap->Dictionary[0]->Value instanceof DictionaryObjectItem)
                return '/Yes';

            foreach($ap->Dictionary[0]->Value->Dictionary as $dictionaryItem)
            {
                if($dictionaryItem->Key=='/Off')
                    continue;

                return $dictionaryItem->Key;
            }

            return '/Yes';

        }
    }

    public function SetFieldValue($formField)
    {
        $fieldSettings=$this->GetFieldSettings();
        if($fieldSettings==null)
            return;

        if($formField->Contains($fieldSettings->CheckValues))
        {
            $this->SetValue('/AS',PrecompiledStringJSONItem::CreateFromText($this->Generator,$this,$this->GetCheckedStateName()));
            $this->SetValue('/V',PrecompiledStringJSONItem::CreateFromText($this->Generator,$this,$this->GetCheckedStateName()));
        }else{
            $this->SetValue('/AS',PrecompiledStringJSONItem::CreateFromText($this->Generator,$this,'/Off'));
            $this->SetValue('/V',PrecompiledStringJSONItem::CreateFromText($this->Generator,$this,'/Off'));
        }
    }
}