<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
use rnwcinv\htmlgenerator\fields\PDFFieldBase;

abstract class PDFSubFieldBase extends PDFFieldBase {
    public $WCFieldName;
    private $fieldOptions=null;


    public function __construct($options,$orderValueRetriever)
    {
        parent::__construct($options,$orderValueRetriever);
        $this->fieldOptions=$this->GetPropertyValue('fieldOptions');
    }

    public function GetStringValue($propertyName)
    {
        if(!isset($this->fieldOptions->$propertyName))
            return '';
        return $this->fieldOptions->$propertyName;
    }

    public function GetBooleanValue($propertyName,$defaultValue=false)
    {
        if(!isset($this->fieldOptions->$propertyName))
            return $defaultValue;
        return $this->fieldOptions->$propertyName=='true';
    }

    public function GetNumericValue($propertyName)
    {
        if(!isset($this->fieldOptions->$propertyName))
            return 0;
        return intval($this->fieldOptions->$propertyName);
    }

    public function GetFieldValue(){
        if($this->orderValueRetriever->useTestData)
           return $this->GetTestFieldValue();
        return $this->GetRealFieldValue();
    }

    public abstract function GetTestFieldValue();
    public abstract function GetWCFieldName();

    public function GetFieldValueFromOrder(){
        return $this->orderValueRetriever->get($this->GetWCFieldName());
    }
    public function GetRealFieldValue($format=''){
        return $this->orderValueRetriever->get($this->GetWCFieldName());
    }

    protected function GetFieldOptions($propertyName,$defaultValue='')
    {
        if($this->options!=null)
        {
            if ($this->fieldOptions == null)
            {
                $this->fieldOptions = $this->GetPropertyValue('fieldOptions');
            }

            if (isset($this->fieldOptions->$propertyName))
                return $this->fieldOptions->$propertyName;
        }
        return $defaultValue;
    }




    public function GetLabelText(){
        if($this->orderValueRetriever->useTestData)
        {
            return $this->GetFieldOptions('label');
        }
        return $this->orderValueRetriever->TranslateText($this->GetPropertyValue('fieldID'),'text',$this->GetFieldOptions('label'));

    }
    public function FormatValue($value,$format='')
    {
        return $value;
    }


    protected function GetInternalValueText($format=''){
        if($this->orderValueRetriever->useTestData)
            return $this->FormatValue($this->GetTestFieldValue(),$format);
        return $this->FormatValue($this->GetRealFieldValue($format),$format);

    }

    public function GenerateFieldValueContainer($containerStyle,$valueStyle)
    {
        return  '<td class="fieldValueContainer" style="'.$containerStyle.'"><p class="fieldValue" style="'.$valueStyle.'">'.$this->GetInternalValueText().'</p></td>';
    }



    public function InternalGetHTML()
    {
        if($this->GetFieldOptions('SkipIfEmpty',false)&&$this->IsEmpty())
            return '';
        $labelPosition=$this->GetFieldOptions('labelPosition','top');
        $html='';
        if($labelPosition=='left')
        {
            $html='<table style="width:100%;"> <tbody>'.
                                        '<tr>'.
                                            '<td style="vertical-align: top;"><p class="fieldLabel" style="margin:0;padding:0;" >'.$this->GetLabelText().'</p></td>'.
                                            $this->GenerateFieldValueContainer('vertical-align: top;','margin:0;padding:0;text-align: right;').
                                        '</tr>'.
                                    '</tbody>'.
                           '</table>';
                    
        }

        if($labelPosition=='right')
        {
            $html='<table style="width:100%;">'.
                                    '<tbody>'.
                                        '<tr>'.
                                            $this->GenerateFieldValueContainer('','margin:0;padding:0;text-align: left;vertical-align: top;').
                                            '<td style="text-align: right;vertical-align: top;"><p style="margin:0;padding:0;" class="fieldLabel" >'.$this->GetLabelText().'</p></td>'.
                                        '</tr>'.
                                    '</tbody>'.
                           '</table>';
        }

        if($labelPosition=='top')
        {
            $html='<table style="width:100%;">'.
                                    '<tbody>'.
                                        '<tr>'.
                                            '<td style="vertical-align: top;"><p class="fieldLabel" style="margin:0;padding:0;" >'.$this->GetLabelText().'</p></td>'.
                                        '</tr>'.
                                        '<tr>'.
                                            $this->GenerateFieldValueContainer('vertical-align: top;','text-align: left;margin:0;padding:0;').
                                        '</tr>'.
                                    '</tbody>'.
                           '</table>';
        }
        return $html;
    }


    public function GetTextValue(){
        return $this->GetInternalValueText('plain');
    }

    public function IsEmpty(){
        if($this->orderValueRetriever->useTestData)
            return false;

        return $this->GetInternalValueText()=='';
    }


}