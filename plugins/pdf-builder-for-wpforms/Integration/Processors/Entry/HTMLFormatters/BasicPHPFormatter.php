<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 7:33 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters;


class BasicPHPFormatter extends PHPFormatterBase
{
    public $Value;

    public function __construct($Value,$field=null)
    {
        parent::__construct($field);
        $this->Value = $Value;
    }

    public function __toString()
    {
        return '<p>'.nl2br(esc_html($this->Value)).'</p>';
    }

    public function ToText(){
        return $this->Value;
    }

    public function IsEmpty(){
        return trim($this->Value)=='';
    }


}