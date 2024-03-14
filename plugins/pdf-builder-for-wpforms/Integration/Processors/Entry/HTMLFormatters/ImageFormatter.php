<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 7:33 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters;


class ImageFormatter extends PHPFormatterBase
{
    public $URLs=[];

    public function __construct($urls,$field=null)
    {
        parent::__construct($field);
        if(!is_array($urls))
            $urls=[$urls];

        foreach($urls as $currentURL)
        {
            if(trim($currentURL)!='')
                $this->URLs[]=$currentURL;
        }
    }

    public function __toString()
    {
        $html='';
        foreach($this->URLs as $currentURL)
            $html.='<img style="max-width:200px" src="'.esc_attr($currentURL).'"/>';
        return $html;
    }

    public function ToText(){
        return implode(', ',$this->URLs);
    }

    public function IsEmpty(){
        return count($this->URLs)==0;
    }


}