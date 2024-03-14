<?php

namespace WunderAuto\Types\Actions;

/**
 * Class RunWorkflow
 */
class RunWorkflow extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Run workflow', 'wunderauto');
        $this->description = __('Run another workflow', 'wunderauto');
        $this->group       = 'Advanced';
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        return true;
    }
}
