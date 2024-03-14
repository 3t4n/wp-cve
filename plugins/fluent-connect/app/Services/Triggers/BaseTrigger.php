<?php

namespace FluentConnect\App\Services\Triggers;

use FluentConnect\App\Services\ConnectStores;

abstract class BaseTrigger
{
    protected $triggerProvider = '';

    protected $triggerName = '';

    protected $priority = 10;

    protected $triggerArgs = 1;

    public function __construct()
    {
        add_filter('fluent_connect_trigger_'.$this->triggerName.'_arg_num', function ($num) {
            return $this->triggerArgs;
        });
    }

    public function __get($name)
    {
        if($name == 'triggerProvider') {
            return $this->triggerProvider;
        }
        if($name == 'triggerName') {
            return $this->triggerName;
        }

        if($name == 'priority') {
            return $this->priority;
        }

        if($name == 'triggerArgs') {
            return $this->triggerArgs;
        }

        return false;
    }

    abstract public function getInfo();

    abstract public function getSettingsDefaults();

    abstract public function getSettingsFields($trigger);

    public function isEnabled()
    {
        return true;
    }

    abstract public function getFormattedData($trigger, $args);

}
