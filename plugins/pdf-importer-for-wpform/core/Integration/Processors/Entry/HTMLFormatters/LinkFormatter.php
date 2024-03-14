<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 7:33 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters;


class LinkFormatter extends PHPFormatterBase
{


    private $url;
    private $title;

    public function __construct($url, $title)
    {
        $this->url = $url;
        $this->title = $title;
    }


    public function __toString()
    {
       return '<a target="_blank" href="'.$this->url.'">'.\esc_html($this->title).'</a>';
    }


    public function IsEmpty(){
        return trim($this->url)=='';
    }


    public function ToText()
    {
        return $this->url;
    }
}