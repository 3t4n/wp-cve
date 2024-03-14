<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields;


use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\EntryItemBase;
use rnpdfimporter\JPDFGenerator\JSONItem\DictionaryObjectItem;
use rnpdfimporter\JPDFGenerator\JSONItem\PrecompiledStringJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\FieldJSONItem;
use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\pr\JPDFGenerator\Appearance\DefaultImageAppearance;

class ImagePDFField extends FieldJSONItem
{
    private $checkedStateValue=null;

    public function GetType()
    {
        return 'Btn';
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

        return null;
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

    /**
     * @param EntryItemBase $formField
     */
    public function SetFieldValue($formField)
    {
        $appearance=new DefaultImageAppearance($this);
        $appearance->Generate($formField->GetText());

    }
}