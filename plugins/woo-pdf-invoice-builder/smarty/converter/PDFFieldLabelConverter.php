<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 12/11/2017
 * Time: 11:54 AM
 */

class PDFFieldLabelConverter  extends PDFConverterBase
{
    public $field;
    public function __construct($options, $useTestData = false, $order,$field,$translator)
    {
        parent::__construct($options, $useTestData, $order,$translator);
        $this->field=$field;
    }


    public function GetTestFieldValue()
    {
        if(!isset($this->field))
            return '';
        $options= $this->field['fieldOptions'];
        return $options['label'];
    }

    public function GetRealFieldValue()
    {
        if(!isset($this->field))
            return '';
        $field=$this->field;
        $options= $this->field['fieldOptions'];

        return $this->TranslateText($field['fieldID'],'text', $options['label']);

    }

    public function GetWCFieldName()
    {
        return "a";
    }
}