<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 5:02 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields;




class RatingFieldSettings extends FieldSettingsBase
{
    public $Scale;
    public function __construct()
    {
        $this->UseInConditions=true;
    }

    public function Initialize($FieldId, $Label, $SubType,$scale=5)
    {
        $this->Scale=$scale;
        return parent::Initialize($FieldId, $Label, $SubType);
    }


    public function GetType()
    {
        return 'Rating';
    }

    public function InitializeFromOptions($options)
    {
        parent::InitializeFromOptions($options);
        $this->Scale=$options->Scale;
    }


}