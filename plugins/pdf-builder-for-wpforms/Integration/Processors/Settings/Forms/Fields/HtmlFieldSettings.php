<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 5:02 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields;




use rednaoformpdfbuilder\Utils\Sanitizer;

class HtmlFieldSettings extends FieldSettingsBase
{

    public $Content='';

    public function SetContent($content)
    {
        $this->Content=$content;
        return $this;
    }

    public function __construct()
    {
        $this->UseInConditions=false;
    }

    public function GetType()
    {
        return 'Html';
    }

    public function InitializeFromOptions($options)
    {
        parent::InitializeFromOptions($options);
        $this->Content=Sanitizer::GetStringValueFromPath($options,['Content']);
    }
}