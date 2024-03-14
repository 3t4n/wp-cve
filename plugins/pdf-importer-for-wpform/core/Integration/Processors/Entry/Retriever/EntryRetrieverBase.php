<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 4:20 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry\Retriever;


use rnpdfimporter\core\core\Loader;
use rnpdfimporter\core\DTO\FieldSummaryOptions;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\EntryItemBase;
use rnpdfimporter\core\Integration\Processors\Entry\EntryProcessorBase;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\FormSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\FieldSettingsFactoryBase;

abstract class EntryRetrieverBase
{
    /**
     * @var Loader
     */
    public $Loader;
    /** @var EntryItemBase[] */
    public $EntryItems;
    public $FieldDictionary;
    public $Raw;
    /** @var FieldSettingsBase[] */
    public $OriginalFieldSettings;
    public $OriginalId;
    public function __construct($loader)
    {
        $this->OriginalFieldSettings=[];
        $this->Loader=$loader;
    }

    public function InitializeFromOriginalEntryId($id)
    {
        global $wpdb;
        $entryId=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->RECORDS_TABLE.' where original_id=%s',$id));
        if($entryId===false)
            return false;
        return $this->InitializeFromEntryId($entryId);
    }

    public function GetRaw($id,$path='')
    {
        if(!isset($this->Raw)||!isset($this->Raw->Fields)|| !isset($this->Raw->Fields->$id))
            return '';

        $data=$this->Raw->Fields->$id;
        if(!isset($data->$path))
            return '';

        return $data->$path;

    }

    public function InitializeFromEntryId($entryId)
    {
        global $wpdb;
        $recordData= $wpdb->get_results($wpdb->prepare('select fields,form.fields original_fields, entry,seq_num,date,raw,record.original_id from '.$this->Loader->RECORDS_TABLE.' record join '.$this->Loader->FormConfigTable.' form on form.id=record.form_id where record.id=%d' ,$entryId));
        if($recordData==false||count($recordData)==0)
            return false;

        $raw=array();
        $this->OriginalId=$recordData[0]->original_id;

        $originalFields=\json_decode($recordData[0]->original_fields);
        $this->GenerateOriginalFields($originalFields);


        if(isset($recordData[0]->raw))
        {
            $raw = \json_decode($recordData[0]->raw);
            if($raw==false)
                $raw=array();
        }

        $this->Raw=$raw;
        $fields=\json_decode($recordData[0]->fields);
        $entry=\json_decode($recordData[0]->entry);

        $fields[]=(object)array(
            'Id'=>'_seq_num',
            'Label'=>'Number',
            'Type'=>'Text',
            'SubType'=>'number'
        );

        $entry[]=(object)array(
            'Value'=>$recordData[0]->seq_num,
            '_fieldId'=>'_seq_num'
        );


        $fields[]=(object)array(
            'Id'=>'_creation_date',
            'Label'=>'Creation Date',
            'TimeFormat'=>"g:i A",
            'DateFormat'=>'m/d/Y',
            'Type'=>'Date',
            'SubType'=>'date-time'
        );

        $unix=strtotime($recordData[0]->date);
        $entry[]=(object)array(
            'Value'=>date('m/d/Y',$unix),
            'Date'=>date('m/d/Y',$unix),
            'Time'=>'',
            'Unix'=>$unix,
            '_fieldId'=>'_creation_date'
        );

        $formSettings=new FormSettings();
        $formSettings->Id='';
        $formSettings->Name='';

        $fieldSettingsFactory=$this->GetFieldSettingsFactory();

        foreach($fields as $field)
        {
            $formSettings->AddFields($fieldSettingsFactory->GetFieldByOptions($field));
        }


        $entryProcessor=$this->GetEntryProcessor();
        $this->EntryItems=$entryProcessor->InflateEntry($entry,$formSettings->Fields);
        $this->CreateFieldDictionary();
        return true;
    }

    /**
     * @param $entryItems EntryItemBase[]
     */
    public function InitializeByEntryItems($entryItems,$raw=null,$originalFields=null,$originalId='')
    {
        $originalFields=\json_decode($originalFields);
        if($originalFields!=null)
            $this->GenerateOriginalFields($originalFields);
        $this->EntryItems=$entryItems;
        $this->CreateFieldDictionary();
        $this->Raw=$raw;
        $this->OriginalId=$originalId;
        return true;
    }

    /**
     * @return FieldSettingsFactoryBase
     */
    public abstract function GetFieldSettingsFactory();

    /**
     * @return EntryProcessorBase
     */
    protected abstract function GetEntryProcessor();

    public function GetHtmlByFieldId($fieldId,$style='standard')
    {
        if(!isset($this->FieldDictionary[$fieldId]))
            return null;
        /** @var EntryItemBase $field */
        $field=$this->FieldDictionary[$fieldId];
        return $field->GetHtml($style);
    }

    /**
     * @param $fieldId
     * @return FieldSummaryOptions
     */
    public function GetFieldSettingsById($fieldId)
    {
        if(isset($this->FieldDictionary[$fieldId]))
        {
            /** @var EntryItemBase $entryItem */
            $entryItem=$this->FieldDictionary[$fieldId];
            return $entryItem->Field;
        }
        else
            return null;
    }

    public function GetFieldById($fieldId)
    {
        if(isset($this->FieldDictionary[$fieldId]))
        {
            /** @var EntryItemBase $entryItem */
            $entryItem=$this->FieldDictionary[$fieldId];
            return $entryItem;
        }
        else
            return null;
    }


    public function GetValueById($fieldId)
    {
        foreach($this->EntryItems as $currentEntry)
        {
            if($currentEntry->Field->Id==$fieldId)
                return $currentEntry;
        }

        return null;
    }

    private function CreateFieldDictionary()
    {
        $this->FieldDictionary=array();
        foreach($this->EntryItems as $item)
        {
            $this->FieldDictionary[$item->Field->Id]=$item;
        }
    }

    public abstract function GetProductItems();

    private function GenerateOriginalFields($originalFields)
    {
        $this->OriginalFieldSettings=[];
        $factory=$this->Loader->CreateEntryRetriever()->GetFieldSettingsFactory();
        foreach($originalFields as $currentOriginalFields)
        {
            $this->OriginalFieldSettings[]=$factory->GetFieldByOptions($currentOriginalFields);
        }
    }

    public function GetGeoLocation()
    {
        return '';
    }

}