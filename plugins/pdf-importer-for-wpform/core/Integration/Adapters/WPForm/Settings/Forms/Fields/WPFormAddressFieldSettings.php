<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 6:15 AM
 */

namespace rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\Fields;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;

class WPFormAddressFieldSettings extends FieldSettingsBase
{
    public $HideCountry;
    public $HideAddress2;
    public $HidePostal;

    public $Address1Label;
    public $Address2Label;
    public $CityLabel;
    public $StateLabel;
    public $ZipLabel;
    public $CountryLabel;

    public function __construct()
    {
        $this->HideCountry=false;
        $this->HideAddress2=false;
        $this->HidePostal=false;
    }


    public function GetType()
    {
        return 'Address';
    }




    public function SetHidePostal($value)
    {
        $this->HidePostal=$value;
    }

    public function SetHideAddress2($value)
    {
        $this->HideAddress2=$value;
    }

    public function SetHideCountry($value)
    {
        $this->HideCountry=$value;
    }

    public function InitializeFromOptions($options)
    {
        $this->HideCountry=$this->GetBoolValue($options,['HideCountry']);
        $this->HideAddress2=$this->GetBoolValue($options,['HideAddress2']);
        $this->HidePostal=$this->GetBoolValue($options,['HidePostal']);


        $this->Address1Label=$this->GetValue($options,['Address1Label']);
        $this->Address2Label=$this->GetValue($options,['Address2Label']);
        $this->CityLabel=$this->GetValue($options,['CityLabel']);
        $this->StateLabel=$this->GetValue($options,['StateLabel']);
        $this->ZipLabel=$this->GetValue($options,['ZipLabel']);
        $this->CountryLabel=$this->GetValue($options,['CountryLabel']);

        parent::InitializeFromOptions($options);
    }


}