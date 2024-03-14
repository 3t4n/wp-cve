<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 6:19 AM
 */

namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;

class WPFormNameFieldSettings extends FieldSettingsBase
{

    public $Format;

    public function __construct()
    {
        $this->Type='simple';
        $this->UseInConditions=false;

    }


    public function GetType()
    {
        return 'Name';
    }

    public function InitializeFromOptions($options)
    {
        $this->Format=$this->GetStringValue($options,['Format']);
        parent::InitializeFromOptions($options);
    }


}