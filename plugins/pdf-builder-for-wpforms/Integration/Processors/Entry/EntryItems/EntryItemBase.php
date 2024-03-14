<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:49 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems;


use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
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

    public function Contains($value)
    {
        $text=$this->GetHtml()->ToText();
        return strpos($text,$value)!==false;
    }

    protected abstract function InternalGetObjectToSave();
    public abstract function InitializeWithOptions($field,$options);

    /**
     * @return PHPFormatterBase
     */
    public abstract function GetHtml($style='standard');



}