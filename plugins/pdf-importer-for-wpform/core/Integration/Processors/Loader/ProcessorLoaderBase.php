<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/19/2019
 * Time: 11:37 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Loader;


use rnpdfimporter\core\core\Loader;
use rnpdfimporter\core\Integration\Processors\Entry\EntryProcessorBase;
use rnpdfimporter\core\Integration\Processors\FormProcessor\FormProcessorBase;

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

