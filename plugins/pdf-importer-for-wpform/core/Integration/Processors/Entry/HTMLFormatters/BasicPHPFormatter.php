<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 7:33 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters;


class BasicPHPFormatter extends PHPFormatterBase
{
    public $Value;

    public function __construct($Value)
    {
        $this->Value = $Value;
    }

    public function __toString()
    {
        return '<p>'.esc_html($this->Value).'</p>';
    }

    public function ToText(){
        return $this->Value;
    }

    public function IsEmpty(){
        return trim($this->Value)=='';
    }


}