<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/19/2019
 * Time: 11:37 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Loader;


use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryProcessorBase;
use rednaoformpdfbuilder\Integration\Processors\FormProcessor\FormProcessorBase;

abstract class ProcessorLoaderBase
{
    /** @var Loader */
    public $Loader;
    /** @var FormProcessorBase */
    public $FormProcessor;
    /** @var EntryProcessorBase */
    public $EntryProcessor;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }


    public abstract function Initialize();
}

