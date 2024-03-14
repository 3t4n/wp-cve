<?php

namespace rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems;

use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\DocumentGenerator;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;
use rednaoformpdfbuilder\Utils\Sanitizer;

class DateTimeEntryItem extends EntryItemBase
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

    public function GetHtml($style='standard',$field=null)
    {
        if($style=='similar')
        {
            $field = $this->Field;
            $formatter = new SingleBoxFormatter($this->FormattedDateTime
            );

            return $formatter;
        }

        $document=DocumentGenerator::$LatestDocument;

        $dateFormat=Sanitizer::GetValueFromPath($document,['options','DocumentSettings','DateFormat'],'');
        if($dateFormat!=null&&is_numeric($this->Unix)&&$this->Unix>0)
        {
            $dateToUse=date($dateFormat,$this->Unix);
        }else
            $dateToUse=$this->FormattedDateTime;

        return new BasicPHPFormatter($dateToUse);
    }
}