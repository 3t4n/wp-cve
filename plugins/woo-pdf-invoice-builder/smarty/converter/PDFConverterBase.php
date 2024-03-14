<?php
require_once RednaoWooCommercePDFInvoice::$DIR.'smarty/PDFAbstractDataRetriever.php';
abstract class PDFConverterBase extends PDFAbstractDataRetriever{
    public $useTestData;
    public $options;
    public $WCFieldName;

    public function __construct($options,$useTestData=false,$order,$translator)
    {
        parent::__construct($order,$translator);
        $this->options=$options;
        $this->useTestData=$useTestData;
    }

    public function GetStringValue($propertyName)
    {
        if(!isset($this->options[$propertyName]))
            return '';
        return $this->options[$propertyName];
    }

    public function GetBooleanValue($propertyName)
    {
        if(!isset($this->options[$propertyName]))
            return false;
        return $this->options[$propertyName]=='true';
    }

    public function GetNumericValue($propertyName)
    {
        if(!isset($this->options[$propertyName]))
            return 0;
        return intval($this->options[$propertyName]);
    }

    public function GetFieldValue(){
        if($this->useTestData)
           return $this->GetTestFieldValue();
        return $this->GetRealFieldValue();
    }

    public abstract function GetTestFieldValue();
    public abstract function GetWCFieldName();

    public function GetFieldValueFromOrder(){
        $number=$this->GetOrderProperty($this->GetFieldName());
    }
    public function GetRealFieldValue(){
        return $this->order->get($this->GetWCFieldName());
    }

    public function __toString()
    {
        return $this->GetFieldValue();
    }




}