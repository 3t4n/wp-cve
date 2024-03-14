<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 7:33 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters;


class MultipleLineFormatter  extends PHPFormatterBase
{
    private $lines;

    public function __construct()
    {

        $this->lines=[];
    }

    public function AddLine($line)
    {
        $this->lines[]=$line;
    }


    public function __toString()
    {
        $text='';
        foreach($this->lines as $line)
            $text.= '<p>'.esc_html($line).'</p>';

        return $text;
    }

    public function IsEmpty(){
        return \count($this->lines)==0;
    }


    public function ToText()
    {
        return implode(', ',$this->lines);
    }
}