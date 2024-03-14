<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 4:26 AM
 */

namespace rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms;


use Exception;
use rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormDateFieldSettings;
use rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormNameFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\FieldSettingsFactoryBase;

class WPFormFieldSettingsFactory extends FieldSettingsFactoryBase
{
    /**
     * @param $options
     * @return FieldSettingsBase
     * @throws Exception
     */
    public function GetFieldByOptions($options)
    {
        $field= parent::GetFieldByOptions($options);
        if($field!=null)
            return $field;

        switch ($options->Type)
        {
            case 'Address':
                $field=new WPFormAddressFieldSettings();
                break;
            case 'Date':
                $field=new WPFormDateFieldSettings();
                break;
            case 'Name':
                $field=new WPFormNameFieldSettings();
                break;
        }

        if($field==null)
            throw new Exception('Invalid field settings type '.$options->Type);

        $field->InitializeFromOptions($options);
        return $field;
    }


}