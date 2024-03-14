<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields;


use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\EntryItemBase;
use rnpdfimporter\JPDFGenerator\JSONItem\DictionaryObjectItem;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\FieldJSONItem;

class FieldWithParent extends FieldJSONItem
{
    public $RealField=null;
    public function __construct($generator, $parent, $data)
    {
        parent::__construct($generator, $parent, $data);

    }

    public function GetValue($key)
    {
        foreach($this->Dictionary as $item)
        {
            if($item->Key==$key)
            {
                return $item->Value;
            }
        }

        $parent=$this->GetParentField();
        if($parent!=null&&$parent->Object instanceof DictionaryObjectItem)
        {
            return $parent->Object->GetValue($key);
        }

        return null;
    }

    /**
     * @return FieldJSONItem
     */
    public function GetRealField(){
        if($this->RealField==null)
        {
            $fieldType=$this->GetValue('/FT');
            if($fieldType!=null)
            {
                $fieldType=$fieldType->GetText();
                switch ($fieldType)
                {
                    case '/Tx':
                        $this->RealField= new TextPDFField($this->Generator, $this->Parent,$this->Dictionary);
                        break;
                    case '/Btn':
                        $ff=$this->GetNumberValue('/Ff',0);
                        if($ff&1<<16)
                        {
                            $this->RealField= new ImagePDFField($this->Generator, $this->Parent,$this->Dictionary);
                        }else
                            $this->RealField= new ButtonPDFField($this->Generator, $this->Parent,$this->Dictionary);
                        break;
                    case '/Ch':
                        $this->RealField=new ChoicePDFField($this->Generator, $this->Parent,$this->Dictionary);
                        break;
                    case '/Sig':
                        $this->RealField=new SignaturePDFField($this->Generator, $this->Parent,$this->Dictionary);
                        break;

                }
            }

            if($this->RealField!=null)
                $this->RealField->Dictionary=&$this->Dictionary;
        }

        return $this->RealField;

    }

    public function GetParentField()
    {
        $parent=$this->GetValue('/Parent');
        if($parent==null)
            return null;

        $text=$parent->GetText(0);
        $splitText=\explode(' ',$text);
        if(count($splitText)<2)
            return null;

        $field=$this->Generator->GetObjectByGenerationAndObjectNumber($splitText[1],$splitText[0]);
        return $field;

    }

    public function GetType()
    {
        return "FP";
    }

    public function SetFieldValue($formField)
    {
        $field=$this->GetRealField();
        $field->SetFieldValue($formField);
    }
}