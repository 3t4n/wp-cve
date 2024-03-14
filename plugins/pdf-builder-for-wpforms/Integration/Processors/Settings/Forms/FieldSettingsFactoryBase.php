<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 4:21 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Settings\Forms;


use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FileUploadFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\HtmlFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\NumberFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\TextFieldSettings;

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
            case 'Html':
                $field=new HtmlFieldSettings();
        }

        if($field!=null)
            $field->InitializeFromOptions($options);
        return $field;
    }
}