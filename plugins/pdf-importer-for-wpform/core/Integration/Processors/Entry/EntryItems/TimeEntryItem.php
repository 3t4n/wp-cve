<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems;


use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;

class TimeEntryItem extends EntryItemBase
{
    public $Unix;
    public $Value;


    public function GetText()
    {
        return $this->Value;

    }

    public function SetUnix($value)
    {
        $this->Unix=$value;
        return $this;
    }
    public function SetValue($value)
    {
        $this->Value=$value;
        return $this;
    }

    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Unix'=>$this->Unix,
            'Value'=>$this->Value
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Unix))
            $this->Unix=$options->Unix;

        if(isset($options->Value))
            $this->Value=$options->Value;
    }

    public function GetHtml($style = 'standard')
    {
        // TODO: Implement GetHtml() method.
    }
}