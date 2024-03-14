<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/19/2019
 * Time: 11:38 AM
 */

namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\Loader;


use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\WPFormEntryProcessor;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;
use rednaoformpdfbuilder\Integration\Processors\Loader\ProcessorLoaderBase;

class WPFormProcessorLoader extends ProcessorLoaderBase
{

    public function Initialize()
    {
        $this->FormProcessor=new WPFormFormProcessor($this->Loader);
        $this->EntryProcessor=new WPFormEntryProcessor($this->Loader);
    }
}