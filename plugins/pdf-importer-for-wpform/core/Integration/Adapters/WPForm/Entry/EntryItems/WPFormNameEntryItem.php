<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 5:15 AM
 */

namespace rnpdfimporter\core\Integration\Adapters\WPForm\Entry\EntryItems;


use rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormNameFieldSettings;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\EntryItemBase;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\MultipleBoxFormatter;

class WPFormNameEntryItem extends EntryItemBase
{
    public $FirstName;
    public $LastName;
    public $Middle;
    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->FirstName.' '.$this->LastName,
            'FirstName'=>$this->FirstName,
            'LastName'=>$this->LastName,
            'Middle'=>''
        );
    }


    public function InitializeWithValues($field,$firstName,$lastName='',$middle='')
    {
        $this->Initialize($field);
        $this->FirstName=$firstName;
        $this->LastName=$lastName;
        $this->Middle=$middle;
        return $this;
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->FirstName))
            $this->FirstName=$options->FirstName;
        if(isset($options->LastName))
            $this->LastName=$options->LastName;

        if(isset($options->Middle))
            $this->Middle=$options->Middle;
    }

    public function GetHtml($style='standard')
    {
        if($style=='similar')
        {
            /** @var WPFormNameFieldSettings $field */
            $field=$this->Field;
            $formatter=new MultipleBoxFormatter();
            $row=$formatter->CreateRow();
            switch ($field->Format)
            {
                case 'simple':
                    $row->AddColumn('Name',$this->FirstName,100);
                    break;
                case 'first-middle-last':
                    $row->AddColumn('First',$this->FirstName,40);
                    $row->AddColumn('Middle',$this->Middle,20);
                    $row->AddColumn('Last',$this->LastName,40);
                    break;
                case 'first-last':
                    $row->AddColumn('First',$this->FirstName,50);
                    $row->AddColumn('Last',$this->LastName,50);
                    break;
            }

            return $formatter;

        }

        return new BasicPHPFormatter($this->FirstName.' '.$this->LastName);
    }


    public function GetText()
    {
        $formatter=new MultipleBoxFormatter();
        $row=$formatter->CreateRow();
        switch ($this->Field->Format)
        {
            case 'simple':
                $row->AddColumn('Name',$this->FirstName,100);
                break;
            case 'first-middle-last':
                $row->AddColumn('First',$this->FirstName,40);
                $row->AddColumn('Middle',$this->Middle,20);
                $row->AddColumn('Last',$this->LastName,40);
                break;
            case 'first-last':
                $row->AddColumn('First',$this->FirstName,50);
                $row->AddColumn('Last',$this->LastName,50);
                break;
        }

        return $formatter->ToText();
    }
}