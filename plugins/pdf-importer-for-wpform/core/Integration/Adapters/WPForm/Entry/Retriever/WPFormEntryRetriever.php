<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 4:30 AM
 */

namespace rnpdfimporter\core\Integration\Adapters\WPForm\Entry\Retriever;


use rnpdfimporter\core\Integration\Adapters\WPForm\Entry\WPFormEntryProcessor;
use rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\WPFormFieldSettingsFactory;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\MultipleSelectionEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\MultipleSelectionValueItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryProcessorBase;
use rnpdfimporter\core\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\FieldSettingsFactoryBase;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\FormSettings;

class WPFormEntryRetriever extends EntryRetrieverBase
{


    /**
     * @return FieldSettingsFactoryBase
     */
    public function GetFieldSettingsFactory()
    {
        return new WPFormFieldSettingsFactory();
    }

    /**
     * @return EntryProcessorBase
     */
    protected function GetEntryProcessor()
    {
        return new WPFormEntryProcessor($this->Loader);
    }

    public function GetGeoLocation()
    {
        $location = wpforms()->entry_meta->get_meta( array( 'entry_id' => $this->OriginalId, 'type' => 'location', 'number' => 1 ) );
        if($location==null)
            return '';

        $location= json_decode( $location[0]->data, true );
        return $location['latitude'] . ', ' . $location['longitude'];

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