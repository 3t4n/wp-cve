<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 5:05 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields;
class NumberFieldSettings extends FieldSettingsBase
{

    public function __construct()
    {
        $this->UseInConditions=true;
    }

    public function GetType()
    {
        return "Number";
    }
}