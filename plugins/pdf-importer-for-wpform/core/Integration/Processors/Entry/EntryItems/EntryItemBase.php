<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:49 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems;


use Exception;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use stdClass;

abstract class EntryItemBase
{
    /** @var FieldSettingsBase */
    public $Field;
    public function __construct()
    {

    }

    public function Initialize($field)
    {
        $this->Field=$field;
        $this->Data=new stdClass();
        return $this;
    }

    public function GetObjectToSave(){
        $data=$this->InternalGetObjectToSave();
        $data->_fieldId=$this->Field->Id;
        return $data;
    }

    public function GetFieldLabel(){
        return $this->Field->Label;
    }

    public abstract function GetText();

    public function GetNumber(){
        return \floatval($this->GetText());
    }

    protected abstract function InternalGetObjectToSave();
    public abstract function InitializeWithOptions($field,$options);

    /**
     * @return PHPFormatterBase
     */
    public abstract function GetHtml($style='standard');

    public function Contains($value)
    {
        if(!\is_array($value))
            $value=[$value];

        foreach($value as $currentValue)
            if($currentValue==$this->GetText())
                return true;
        return false;
    }

    public function ProcessFieldMethod($methodName)
    {
        return '';
    }


}
