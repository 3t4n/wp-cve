<?php


namespace rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\ListFieldSettings;


class ListColumnSettings
{
    public $Label;
    public $Value;

    public function __construct($label,$value)
    {
        $this->Label=$label;
        $this->Value=$value;
    }


}