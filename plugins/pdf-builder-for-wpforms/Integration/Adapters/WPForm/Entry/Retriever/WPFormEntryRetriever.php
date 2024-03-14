<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 4:30 AM
 */

namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\Retriever;


use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\WPFormEntryProcessor;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\WPFormFieldSettingsFactory;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\MultipleSelectionEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\MultipleSelectionValueItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryProcessorBase;
use rednaoformpdfbuilder\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\FieldSettingsFactoryBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\FormSettings;

class WPFormEntryRetriever extends EntryRetrieverBase
{


    /**
     * @return FieldSettingsFactoryBase
     */
    public function GetFieldSettingsFactory()
    {
        return new WPFormFieldSettingsFactory();
    }

    public function InitializeFromEntryId($id)
    {
        if(wpforms()->pro)
        {
            global $wpdb;
            $entryId = $wpdb->get_var($wpdb->prepare('select original_id from ' . $this->Loader->RECORDS_TABLE . ' where id=%s', $id));
            if ($entryId === false)
                return false;
            return $this->InitializeFromOriginalEntryId($entryId);
        }else
        {
            global $wpdb;
            $recordData= $wpdb->get_results($wpdb->prepare('select form.original_id form_id,record.original_id original_id, fields,form.fields original_fields, entry,seq_num,date,raw from '.$this->Loader->RECORDS_TABLE.' record join '.$this->Loader->FormConfigTable.' form on form.id=record.form_id where record.id=%d' ,$id));
            if($recordData==false||count($recordData)==0)
                return false;

            $raw=array();

            $originalFields=\json_decode($recordData[0]->original_fields);
         //   $this->GenerateOriginalFields($originalFields);


            if(isset($recordData[0]->raw))
            {
                $raw = \json_decode($recordData[0]->raw);
                if($raw==false)
                    $raw=array();
            }

            $raw->form_id=$recordData[0]->form_id;

            $this->Raw=$raw;
            $fields=\json_decode($recordData[0]->fields);
            $entry=\json_decode($recordData[0]->entry);

            $fields[]=(object)array(
                'Id'=>'_seq_num',
                'Label'=>'Number',
                'Type'=>'Text',
                'SubType'=>'number'
            );

            $entryId=$id;
            $entry[]=(object)array(
                'Value'=>$entryId,
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
    }

    public function GetFormId(){
        return $this->Raw->form_id;
    }
    /**
     * @return EntryProcessorBase
     */
    protected function GetEntryProcessor()
    {
        return $this->Loader->ProcessorLoader->EntryProcessor;
    }

    public function GetProductItems()
    {
        $items=array();
        foreach($this->EntryItems as $item)
        {
            switch ($item->Field->SubType)
            {
                case 'payment-select':
                case 'payment-multiple':
                    /** @var MultipleSelectionEntryItem $multipleItem */
                    $multipleItem=$item;

                    foreach($multipleItem->Items as $valueItem)
                    {
                        $items[]= array('name'=>$valueItem->Value,'price'=>$valueItem->Amount);
                    }
                break;
                case 'payment-single':
                $items[]=array('name'=>$item->Field->Label,'price'=>$item->Value);
                    break;
            }
        }

        return $items;
    }
}