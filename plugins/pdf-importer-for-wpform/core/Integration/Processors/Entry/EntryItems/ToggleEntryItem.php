<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems;


use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;

class ToggleEntryItem extends EntryItemBase
{
    public  $IsChecked=false;


    public function SetIsChecked($checked)
    {
        $this->IsChecked=$checked;
        return $this;
    }

    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'IsChecked'=>$this->IsChecked
        );
    }

    public function InitializeWithOptions($field, $options)
    {
        $this->Field=$field;
        if(isset($options->IsChecked))
            $this->IsChecked=$options->IsChecked;
    }

    public function GetHtml($style = 'standard')
    {
        // TODO: Implement GetHtml() method.
    }

    public function GetText()
    {
        if($this->IsChecked)
            return 'True';
        return 'False';
    }
}