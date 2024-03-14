<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 6:21 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields;

class FileUploadFieldSettings extends FieldSettingsBase
{
    public function __construct()
    {
        $this->UseInConditions=true;
    }


    public function GetType()
    {
        return 'FileUpload';
    }
}