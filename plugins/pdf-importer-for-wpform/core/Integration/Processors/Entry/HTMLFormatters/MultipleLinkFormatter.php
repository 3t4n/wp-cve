<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 7:33 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters;


class MultipleLinkFormatter extends PHPFormatterBase
{


    /** @var  LinkFormatterItem[] */
    public $Items;

    public function __construct()
    {
        $this->Items=[];
    }

    public function AddItem($url,$title)
    {
        $this->Items[]=new LinkFormatterItem($url,$title);
    }


    public function __toString()
    {
        $html='';
        foreach($this->Items as $currentItem)
        {
            if($html!='')
                $html.='<br/>';
            $html.='<a target="_blank" href="'.$currentItem->URL .'">'.\esc_html($currentItem->Title).'</a>';
        }

       return $html;
    }

    public function IsEmpty(){
        return \count($this->Items)==0;
    }


    public function ToText()
    {
        $urls=array();
        foreach($this->Items as $currentItem)
        {
            $urls[]=$currentItem->URL;
        }

        return implode(', ',$urls);
    }
}


class LinkFormatterItem
{
    public $URL;
    public $Title;

    public function __construct($url, $title)
    {
        $this->URL = $url;
        $this->Title= $title;
    }

}