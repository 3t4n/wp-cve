<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 5:15 AM
 */

namespace rnpdfimporter\core\Integration\Adapters\WPForm\Entry\EntryItems;


use rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\EntryItemBase;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;

class WPFormDateTimeEntryItem extends EntryItemBase
{
    public $Date;
    public $Time;
    public $Unix;
    public $FormattedDateTime;
    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->FormattedDateTime,
            'Date'=>$this->Date,
            'Time'=>$this->Time,
            'Unix'=>$this->Unix
        );
    }


    public function InitializeWithValues($field,$formattedDateTime,$date,$time,$unix)
    {
        $this->Initialize($field);
        $this->FormattedDateTime=$formattedDateTime;
        $this->Date=$date;
        $this->Time=$time;
        $this->Unix=$unix;

        return $this;
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Value))
            $this->FormattedDateTime=$options->Value;
        if(isset($options->Unix))
            $this->Unix=$options->Unix;
        if(isset($options->Time))
            $this->Time=$options->Time;
        if(isset($options->Date))
            $this->Date=$options->Date;
    }

    public function GetHtml($style='standard')
    {
        if($style=='similar')
        {
            /** @var WPFormAddressFieldSettings $field */
            $field = $this->Field;
            $formatter = new SingleBoxFormatter($this->FormattedDateTime
            );

            return $formatter;
        }

        return new BasicPHPFormatter($this->FormattedDateTime);
    }

    public function GetText()
    {
        return $this->FormattedDateTime;
    }
}