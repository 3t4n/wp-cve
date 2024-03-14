<?php


namespace rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields;


class ComposedFieldItem
{
    public $Id;
    public $Path;
    public $Label;
    public $AddCommaBefore=false;
    /**
     * ComposedFieldItem constructor.
     * @param $Id
     * @param $Path
     */
    public function __construct($Id='', $Path='',$Label='')
    {
        $this->Label=$Label;
        $this->Id = $Id;
        if(!\is_array($Path))
            $Path=[$Path];
        $this->Path = $Path;
    }

    public function AddCommaBefore(){
        $this->AddCommaBefore=true;
        return $this;
    }


    public function InitializeFromOptions($options)
    {
        $this->Id=$options->Id;
        $this->Path=$options->Path;
        $this->Label=$options->Label;
        $this->AddCommaBefore=$options->AddCommaBefore;
    }

}


