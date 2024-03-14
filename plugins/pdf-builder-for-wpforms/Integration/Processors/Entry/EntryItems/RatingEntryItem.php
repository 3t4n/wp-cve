<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:50 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems;


use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\MultipleBoxFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\RawPHPFormatter;
use rednaoformpdfbuilder\Utils\Sanitizer;
use stdClass;

class RatingEntryItem extends EntryItemBase
{
    public $Value;
    public function SetValue($value)
    {
        $this->Value=$value;
        return $this;
    }


    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->Value
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Value))
            $this->Value=$options->Value;
    }

    public function GetHtml($style='standard',$field=null)
    {
        $numberOfStars=intval($this->Value);
        $html='<div>';
        for($i=0;$i<$numberOfStars;$i++)
            $html.='<span class="" style="margin-right:3px;vertical-align:top;font-family:FontAwesome;font-size:30px !important;color:black !important;color:orange;" >&#xf005;</span>';

        $scale=Sanitizer::GetStringValueFromPath($this->Field,['Scale']);
        if($scale!=null&&is_numeric($scale))
            $html.='<span style="line-height:30px;margin-left:5px;vertical-align: bottom">('.$this->Value.'/'.$scale.')</span>';
        $html.='</div>';
        return new RawPHPFormatter($html);
    }


}