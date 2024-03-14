<?php

namespace rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\ListFieldSettings;


use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;



class ListFieldSettings extends FieldSettingsBase
{

    /**
     * @var ListColumnSettings[]
     */
    public $Columns;

    public function __construct()
    {
        $this->Columns=[];
    }


    public function GetType()
    {
        return 'List';
    }

    public function AddColumn($label='',$value='')
    {
        $this->Columns[]=new ListColumnSettings($label,$value);

    }

}