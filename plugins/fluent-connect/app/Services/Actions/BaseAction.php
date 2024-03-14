<?php

namespace FluentConnect\App\Services\Actions;

abstract class BaseAction
{
    protected $actionProvider = '';

    protected $actionName = '';

    public function __get($name)
    {
        if($name == 'actionProvider') {
            return $this->actionProvider;
        }
        if($name == 'actionName') {
            return $this->actionName;
        }

        return false;
    }

    abstract public function getInfo();

    abstract public function getSettingsDefaults();

    abstract public function getSettingsFields($action);

    public function isEnabled()
    {
        return true;
    }

    abstract public function process($action, $data);
}
