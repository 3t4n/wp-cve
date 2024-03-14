<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 12/11/2017
 * Time: 11:54 AM
 */

class PDFTextConverter  extends PDFConverterBase
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
        return $this->field['Text'];
    }

    public function GetRealFieldValue()
    {
        $fieldId=$this->options['fieldID'];

        return $this->TranslateText($fieldId,'text',$this->field['Text']);

    }

    public function GetWCFieldName()
    {
        // TODO: Implement GetWCFieldName() method.
    }
}