<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 4:21 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Settings\Forms;


use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\ComposedFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\DateFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\DateTimeFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FileUploadFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\ListFieldSettings\ListFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\NumberFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\TextFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\TimeFieldSettings;

abstract class FieldSettingsFactoryBase
{
    /**
     * @param $options
     * @return FieldSettingsBase
     */
    public function GetFieldByOptions($options)
    {
        /** @var FieldSettingsBase $field */
        $field=null;
        switch ($options->Type)
        {
            case 'Text':
                $field=new TextFieldSettings();
                break;
            case 'Number':
                $field=new NumberFieldSettings();
                break;
            case 'Multiple':
                $field=new MultipleOptionsFieldSettings();
                break;
            case 'FileUpload':
                $field=new FileUploadFieldSettings();
                break;
            case 'Composed':
                $field=new ComposedFieldSettings();
                break;
            case 'DateTime':
                $field=new DateTimeFieldSettings();
                break;
            case 'Date':
                $field=new DateFieldSettings();
                break;
            case 'Time':
                $field=new TimeFieldSettings();
                break;
            case 'List':
                $field=new ListFieldSettings();
                break;
        }

        if($field!=null)
            $field->InitializeFromOptions($options);
        return $field;
    }
}