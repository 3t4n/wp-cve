<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 5:02 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields;




class TextFieldSettings extends FieldSettingsBase
{

    public function __construct()
    {
        $this->UseInConditions=true;
    }

    public function GetType()
    {
        return 'Text';
    }
}