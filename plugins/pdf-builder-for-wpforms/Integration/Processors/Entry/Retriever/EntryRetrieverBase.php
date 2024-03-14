<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 4:20 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry\Retriever;


use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\DTO\FieldSummaryOptions;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormDateTimeEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\DateTimeEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\EntryItemBase;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\SimpleTextEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryProcessorBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\FormSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\FieldSettingsFactoryBase;

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
    public function __construct($loader)
    {
        $this->OriginalFieldSettings=[];
        $this->Loader=$loader;
    }

    public function InitializeFromEntryId($id)
    {
        global $wpdb;
        $entryId=$wpdb->get_var($wpdb->prepare('select original_id from '.$this->Loader->RECORDS_TABLE.' where id=%s',$id));
        if($entryId===false)
            return false;
        return $this->InitializeFromOriginalEntryId($entryId);
    }

    public function GetRaw($id,$path='')
    {
        if(!isset($this->Raw)||!isset($this->Raw->Fields)|| !isset($this->Raw->Fields->$id))
            return '';

        $data=$this->Raw->Fields->$id;
        if($path=='')
            return $data;
        if(!isset($data->$path))
            return '';

        return $data->$path;

    }

    /**
     * @return EntryItemBase
     */
    public function GetField($fieldId)
    {
        if(!isset($this->FieldDictionary[$fieldId]))
            return null;
        /** @var EntryItemBase $field */
        $field=$this->FieldDictionary[$fieldId];
        return $field;
    }



    public function InitializeFromOriginalEntryId($entryId)
    {
         $raw=$this->Loader->GetEntry($entryId);
        $form=$this->Loader->GetForm(is_array($raw)?$raw['form_id']:$raw->form_id);
        $entry=$this->Loader->ProcessorLoader->EntryProcessor->SerializeEntry($raw,$form);
        return $this->InitializeByEntryItems($entry,$raw,is_array($form)?$form['Fields']:$form->Fields,$entryId,is_array($raw)?$raw['date_created']:$raw->date_created);
    }

    /**
     * @param $entryItems EntryItemBase[]
     */
    public function InitializeByEntryItems($entryItems,$raw=null,$originalFields=null,$entryId=null,$creationDate=null)
    {
        if($entryId!=null)
        {
            $entryItems[]=(new SimpleTextEntryItem())->Initialize((object)array(
                'Id'=>'_seq_num',
                'Label'=>'Number',
                'Type'=>'Text',
                'SubType'=>'number'
            ))->SetValue(intval($entryId));
        }

        if($creationDate!=null)
        {
            $date=strtotime($creationDate);

            $entryItems[]=(new DateTimeEntryItem())->InitializeWithValues((object)array(
                'Id'=>'_creation_date',
                'Label'=>'Creation Date',
                'TimeFormat'=>"g:i A",
                'DateFormat'=>'m/d/Y',
                'Type'=>'Date',
                'SubType'=>'date-time'
            ),date('m/d/Y',$date),date('m/d/Y',$date),'',$date);
        }

        if(is_string($originalFields))
            $originalFields=\json_decode($originalFields);
        if($originalFields!=null)
            $this->GenerateOriginalFields($originalFields);
        $this->EntryItems=$entryItems;
        $this->CreateFieldDictionary();
        $this->Raw=$raw;
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

    public function GetHtmlByFieldId($fieldId,$style='standard',$templateField=null)
    {
        if(!isset($this->FieldDictionary[$fieldId]))
            return null;
        /** @var EntryItemBase $field */
        $field=$this->FieldDictionary[$fieldId];
        return $field->GetHtml($style,$templateField);
    }

    /**
     * @param $fieldId
     * @return FieldSummaryOptions
     */
    public function GetFieldById($fieldId)
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

    public function GetValueById($fieldId)
    {
        foreach($this->EntryItems as $currentEntry)
        {
            if($currentEntry->Field->Id==$fieldId)
                return $currentEntry;
        }

        return null;
    }

    protected function CreateFieldDictionary()
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

}