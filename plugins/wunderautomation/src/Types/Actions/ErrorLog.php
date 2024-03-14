<?php

namespace WunderAuto\Types\Actions;

/**
 * Class ErrorLog
 */
class ErrorLog extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Dump objects to PHP error log', 'wunderauto');
        $this->description = __('Dump objects to PHP error log', 'wunderauto');
        $this->group       = 'Advanced';
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        error_log('WUNDER: ' . json_encode($this->resolver->getObjects(), JSON_PRETTY_PRINT));

        return true;
    }
}
